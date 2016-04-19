<?php

require_once 'ABETDAO.php';
require_once '../Models/Term.php';

class Action_Faculty {

    public function addAnswer($request) {
        $dao = new ABETDAO();
        $query = 'insert into Abet.Answer (RubricsNo, Answer, Weight_Value, Weight_Name, SurveyType_SurveyTypeID, Status_StatusID) values (' .
                $request->get('rubricsNo') . ',\'' . $request->get('answer') . '\', ' . $request->get('weightValue') . ',\'' . $request->get('weight_Name') .
                '\', (select SurveyTypeID from ABET.SurveyType where SurveyName = \'' . $request->get('surveyName') . '\'), '
                . '(select StatusID from Abet.Status where StatusType = \'' . $request->get('statusType') . '\' and StatusName = \'' . $request->get('statusName') . '\')'
                . ');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addQA($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.QA (Question_QID, Answer_AID, Status_StatusID) values (' .
                ' (select QID from Abet.Question where QuestionText = \'' . $request->get('questionText') . '\'), '
                . '(select AID from ABET.Answer where weight_name = \'' . $request->get('weightName') . '\'),'
                . '(select StatusID from abet.status where StatusType = \'' . $request->get('statusType') . '\' and statusname = \'' . $request->get('statusName') . '\')'
                . ');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addStudentSection($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Student_Section (SectionID, Student_StudentID) values (' .
                ' (select SectionID From abet.Section where SectionNum = ' . $request->get('sectionNum') . ' and Faculty_FacultyID = '
                . '(select FacultyID from ABET.Faculty where email = \'' . $request->get('FacultyEmail') . '\')), '
                . '(select StudentID from ABET.Student where SUID = ' . $request->get('studentID') . ')'
                . ');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addQuestion($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.question (RubricsNo, QuestionText, SurveyTypeID, StatusID, StudentOutcome_SOID, Course_CourseID) values (' .
                $request->get('rubricsNO')
                . ',\'' . $request->get('questionText') . '\''
                . ',(select SurveyTypeID from ABET.SurveyType where SurveyName = \'' . $request->get('surveyName') . '\'), '
                . '(select StatusID from abet.status where StatusType = \'' . $request->get('statusType') . '\' and statusname = \'' . $request->get('statusName') . '\')'
                . '(select SOID from ABET.StudentOutcome where SOCode = \'' . $request->get('SOCode') . '\'),'
                . '(select CourseID from ABET.Course where CourseCode = ' . $request->get('courseCode') . ' and ProgramID = '
                . '(select ProgramID from ABET.Program where PNameShort = \'' . $request->get('pnameShort') . '\'))'
                . ');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
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
        $rows = $dao->query($query, $request->get('facultyEmail'), $request->get('semester'), $request->get('PNameShort'));
        // $courseID, $courseName, $courseCode, $courseCredit, $dateActivated, $dateDeactivated, $programID)
        $courses = [];
        foreach ($rows as $row) {
            $courses[] = [
                "PNameShort" => $row["PNameShort"],
                "CourseCode" => $row["CourseCode"]
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
        $rows = $dao->query($query, $request->get('semester'), $request->get('courseCode'), $request->get('PNameShort'));

        $students = [];
        foreach ($rows as $row) {
            $students[] = [
                "SUID" => $row["SUID"],
                "STUName" => $row["StudentName"]
            ];
        }
        return json_encode($students);
    }

    public function getCLOSurvey($request) {
        $dao = new ABETDAO();
        $query = 'SELECT OrderNo, QuestionText, SOCODE, STA.StatusName '
                . 'FROM ABET.Program P , ABET.Course C, ABET.Question Q, ABET.Status STA, ABET.StudentOutcome SO, ABET.SurveyType SUT '
                . 'WHERE P.PROGRAMID = C.PROGRAMID '
                . 'AND C.COURSEID = Q.COURSE_COURSEID '
                . 'AND Q.STATUSID = STA.STATUSID '
                . 'AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND P.PNAMESHORT = ? '
                . 'AND C.COURSECODE = ?'
                . 'AND SUT.SURVEYNAME = \'CLO-Based\''
                . 'AND STA.STATUSTYPE = \'QuestionAnswer\';';
        $rows = $dao->query($query, $request->get('PNameShort'), $request->get('courseCode'));

        $surveys = [];
        foreach ($rows as $row) {
            $surveys[] = [
                "Order" => $row["SUID"],
                "QuestionText" => $row["StudentName"],
                "SOCODE" => $row["SOCODE"],
                "Status" => $row["STA.StatusName"]
            ];
        }
        return json_encode($surveys);
    }

    public function getRubricsSurvey($request) {
        $dao = new ABETDAO();
        $query = 'SELECT SOCODE, RubricsNo, STA.STATUSNAME '
                . 'FROM ABET.Program P, ABET.Course C, ABET.Question Q, ABET.SurveyType SUT, ABET.Status STA, ABET.StudentOutcome SO '
                . 'WHERE P.PROGRAMID = C.PROGRAMID '
                . 'AND C.COURSEID = Q.COURSE_COURSEID '
                . 'AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND Q.STATUSID = STA.STATUSID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND STA.STATUSTYPE = \'Survey\' '
                . 'AND SUT.SURVEYNAME = \'Rubrics-Based\''
                . 'AND P.PNAMESHORT = ?'
                . 'AND C.COURSECODE = ?;';
        $rows = $dao->query($query, $request->get('PNameShort'), $request->get('courseCode'));

        $surveys = [];
        foreach ($rows as $row) {
            $surveys[] = [
                "RubricsNo" => $row["RubricsNo"],
                "Status" => $row["STA.STATUSNAME"],
                "SOCODE" => $row["SOCODE"]
            ];
        }
        return json_encode($surveys);
    }

    public function getRubrics($request) {
        $dao = new ABETDAO();
        $query = 'SELECT SOID, SOCODE FROM ABET.STUDENTOUTOCME NATURAL JOIN ABET.PROGRAM;';
        $rows = $dao->query($query);
        $rubrics = [];
        foreach ($rows as $row) {
            $rubrics[] = [
                "SOID" => $row["SOID"],
                "SOCODE" => $row["SOCODE"]
            ];
        }
        return json_encode($rubrics);
    }

    public function getRubricsQuestions($request) {
        $dao = new ABETDAO();
        $query = 'SELECT QID, RUBRICSNO FROM ABET.QUESTION Q, ABET.STUDENTOUTCOME SO '
                . 'WHERE SO.SOID = Q.STUDENTOUTCOME_SOID '
                . 'AND SO.SOID = ?;';
        $rows = $dao->query($query, $request->get('SOID'));
        $questions = [];
        foreach ($rows as $row) {
            $questions[] = [
                "QID" => $row["QID"],
                "Rubrics" => $row["RUBRICSNO"]
            ];
        }
        return json_encode($questions);
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

    public function getAnswerWeight($request) {
        $dao = new ABETDAO();
        $query = 'SELECT DISTINCT Weight_Name '
                . 'FROM ABET.QUESTION Q, ABET.QA QA, ABET.ANSWER A, ABET.Status STA, ABET.SurveyType SUT '
                . 'WHERE Q.QID = QA.Question_QID '
                . 'AND QA.Answer_AID = A.AID '
                . 'AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND A.SURVEYTYPE_SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND SUT.SURVEYNAME = \'Rubrics-Based\' '
                . 'AND STA.STATUSTYPE = \'QuestionAnswer\' '
                . 'AND STA.STATUSNAME = \'Valid\';';
        $rows = $dao->query($query, $request->get('QID'));
        $weights = [];
        foreach ($rows as $row) {
            $weights[] = [
                "Weight" => $row["Weight_Name"]
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
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('PNameShort'));
        $questions = [];
        foreach ($rows as $row) {
            $questions[] = [
                "Question" => $row["QuestionText"],
                "Weight_Name" => $row["Weight_Name"],
                "Weight_Value" => $row["Weight_Name"]
            ];
        }
        return json_encode($questions);
    }
}
