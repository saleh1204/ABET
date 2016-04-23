<?php

require_once 'ABETDAO.php';
require_once '../Models/Term.php';

class Action_Faculty {

    public function addAnswer($request) {
        $dao = new ABETDAO();
        $query = 'insert into Abet.Answer (RubricsNo, Answer, Weight_Value, Weight_Name, SurveyType_SurveyTypeID, Status_StatusID) values (' .
                $request->get('rubricsNo') . ',\'' . $request->get('answer') . '\', ' . $request->get('weightValue') . ',\'' . $request->get('weightName') .
                '\', (select SurveyTypeID from ABET.SurveyType where SurveyName = \'' . $request->get('surveyName') . '\'), '
                . '(select StatusID from Abet.Status where StatusType = \'' . $request->get('statusType') . '\' and StatusName = \'' . $request->get('statusName') . '\')'
                . ');';
        $dao->excuteQuery($query);
    }

    public function addQA($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.QA (Question_QID, Answer_AID, Status_StatusID) values (' .
                ' (select QID from Abet.Question where QuestionText = \'' . $request->get('questionText') . '\'), '
                . '(select AID from ABET.Answer where weight_name = \'' . $request->get('weightName') . '\'),'
                . '(select StatusID from abet.status where StatusType = \'' . $request->get('statusType') . '\' and statusname = \'' . $request->get('statusName') . '\')'
                . ');';
        $dao->excuteQuery($query);
    }

    public function addStudentSection($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Student_Section (SectionID, Student_StudentID) values (' .
                ' (select SectionID From abet.Section where SectionNum = ' . $request->get('sectionNum') . ' and Faculty_FacultyID = '
                . '(select FacultyID from ABET.Faculty where email = \'' . $request->get('facultyEmail') . '\')), '
                . '(select StudentID from ABET.Student where SUID = ' . $request->get('studentID') . ')'
                . ');';
        $dao->excuteQuery($query);
    }

    public function addQuestion($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.question (RubricsNo, QuestionText, SurveyTypeID, StatusID, StudentOutcome_SOID, Course_CourseID) values (' .
                $request->get('rubricsNo')
                . ',\'' . $request->get('questionText') . '\''
                . ',(select SurveyTypeID from ABET.SurveyType where SurveyName = \'' . $request->get('surveyName') . '\'), '
                . '(select StatusID from abet.status where StatusType = \'' . $request->get('statusType') . '\' and statusname = \'' . $request->get('statusName') . '\')'
                . '(select SOID from ABET.StudentOutcome where SOCode = \'' . $request->get('SOCode') . '\'),'
                . '(select CourseID from ABET.Course where CourseCode = ' . $request->get('courseCode') . ' and ProgramID = '
                . '(select ProgramID from ABET.Program where PNameShort = \'' . $request->get('pnameShort') . '\'))'
                . ');';
        $dao->excuteQuery($query);
    }

    public function getTerms($request) {
        $dao = new ABETDAO();
        $query = 'SELECT SemesterNum FROM ABET.Semester;';
        $rows = $dao->query($query);
        $terms = [];
        foreach ($rows as $row) {
            $terms[] = [ "semester" => $row["SemesterNum"]];
        }
        return json_encode($terms);
    }

    public function getCourses($request) {
        $dao = new ABETDAO();
        $query = 'SELECT PNameShort, CourseCode '
                . 'FROM ABET.Faculty F, ABET.Section SEC, ABET.Semester SEM, ABET.Course C, ABET.Program P '
                . 'WHERE F.FACULTYID = SEC.FACULTY_FACULTYID '
                . 'AND SEM.SEMESTERID = SEC.SEMESTERID '
                . 'AND SEC.COURSEID = C.COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND F.EMAIL = ? '
                . 'AND SEM.SEMESTERNUM = ?'
                . 'AND PNameShort = ?;';
        $rows = $dao->query($query, $request->get('facultyEmail'), $request->get('semester'), $request->get('pnameShort'));
        // $courseID, $courseName, $courseCode, $courseCredit, $dateActivated, $dateDeactivated, $programID)
        $courses = [];
        foreach ($rows as $row) {
            $courses[] = [
                "pnameShort" => $row["PNameShort"],
                "courseCode" => $row["CourseCode"]
            ];
        }
        return json_encode($courses);
    }

    public function getStudents($request) {
        $dao = new ABETDAO();
        $query = 'SELECT SUID, StudentName '
                . 'FROM ABET.Student ST, ABET.Student_Section SS, ABET.Section SEC, ABET.Semester SEM, ABET.Course C, ABET.Program P '
                . 'WHERE ST.STUDENTID = SS.STUDENT_STUDENTID '
                . 'AND SS.SECTIONID = SEC.SECTIONID '
                . 'AND SEC.SemesterID = SEM.SEMESTERID '
                . 'AND SEC.COURSEID = C.COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND SEM.SEMESTERNUM = ? '
                . 'AND C.COURSECODE = ?'
                . 'AND P.PNAMESHORT = ?;';
        $rows = $dao->query($query, $request->get('semester'), $request->get('courseCode'), $request->get('pnameShort'));

        $students = [];
        foreach ($rows as $row) {
            $students[] = [
                "SUID" => $row["SUID"],
                "STUName" => $row["StudentName"]
            ];
        }
        return json_encode($students);
    }

    public function getQuestionStatus($request) {
        $dao = new ABETDAO();
        $query = 'SELECT STATUSNAME FROM ABET.QUESTION NATURAL JOIN ABET.STATUS '
                . 'WHERE QID = ?;';
        $rows = $dao->query($query, $request->get('QID'));
        $status = [];
        foreach ($rows as $row) {
            $status[] = [
                "Status" => $row["STATUSNAME"]
            ];
        }
        return json_encode($status);
    }

    public function getWeights($request) {
        $dao = new ABETDAO();
        $query = 'SELECT DISTINCT Weight_Name '
                . 'FROM ABET.QUESTION Q, ABET.QA QA, ABET.ANSWER A, ABET.Status STA, ABET.SurveyType SUT '
                . 'WHERE Q.QID = QA.Question_QID '
                . 'AND QA.Answer_AID = A.AID '
                . 'AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND A.SURVEYTYPE_SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND SUT.SURVEYNAME = \'CLO-Based\' '
                . 'AND STA.STATUSTYPE = \'QuestionAnswer\' '
                . 'AND STA.STATUSNAME = \'Valid\';';
        $rows = $dao->query($query);
        $weights = [];
        foreach ($rows as $row) {
            $weights[] = [
                "weightName" => $row["Weight_Name"]
            ];
        }
        return json_encode($weights);
    }

    public function getCLOQuestions($request) {
        $dao = new ABETDAO();
        $query = 'SELECT QuestionText, Weight_Name, Weight_Value '
                . 'FROM ABET.Question Q, ABET.Answer A, ABET.QA QA, ABET.Course C, ABET.Program P, ABET.Status, ABET.SurveyType ST '
                . 'WHERE QA.Answer_AID = A.AID '
                . 'AND QA.Question_QID = Q.QID '
                . 'AND Q.COURSE_COURSEID = C.COURSEID '
                . 'AND Q.SURVEYTYPEID = ST.SURVEYTYPEID '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? '
                . 'AND STA.STATUSNAME = \'Valid\' '
                . 'AND ST.SURVEYNAME = \'CLO-Based\''
                . 'ORDER BY QID, WEIGHT_VALUE ;';
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'));
        $questions = [];
        foreach ($rows as $row) {
            $questions[] = [
                "Question" => $row["QuestionText"],
                "weightName" => $row["Weight_Name"],
                "weightValue" => $row["Weight_Name"]
            ];
        }
        echo json_encode($questions);
    }

}
