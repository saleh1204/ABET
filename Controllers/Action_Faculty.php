<?php

require_once 'ABETDAO.php';

//require_once '../Models/Term.php';

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
        $query = 'SELECT SectionID FROM Section WHERE courseID = (SELECT CourseID FROM Course WHERE CourseCode = ? '
                . 'AND ProgramID = (SELECT ProgramID FROM Program WHERE PNameShort = ?)) '
                . 'AND FacultyID = (SELECT FacultyID FROM ABET.Faculty WHERE email = ? ) '
                . 'AND SemesterID = (SELECT SemesterID FROM Semester WHERE semesterNum = ?);';
        $result = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'), $request->get('email'), $request->get('semester'));
        $query = 'insert into abet.Student_Section (SectionID, Student_StudentID) values '
                . '( ?, '
                . '(SELECT StudentID FROM ABET.Student WHERE SUID = ? )'
                . ');';
        $result = $dao->query($query, $result[0]["SectionID"], $request->get('studentID'));
        echo json_encode($result);
    }

    function deleteStudentSection($request) {
        $dao = new ABETDAO();
        $query = 'SELECT StudentID FROM STUDENT WHERE SUID = ?;';
        $result1 = $dao->query($query, $request->get('studentID'));
        $query = 'SELECT SectionID FROM Section WHERE courseID = (SELECT CourseID FROM Course WHERE CourseCode = ? '
                . 'AND ProgramID = (SELECT ProgramID FROM Program WHERE PNameShort = ?)) '
                . 'AND FacultyID = (SELECT FacultyID FROM ABET.Faculty WHERE email = ? ) '
                . 'AND SemesterID = (SELECT SemesterID FROM Semester WHERE semesterNum = ?);';
        $result = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'), $request->get('email'), $request->get('semester'));
        $query = 'DELETE FROM abet.Student_Section WHERE SectionID = ? AND Student_StudentID = ?;';
        $result = $dao->query($query, $result[0]["SectionID"], $result1[0]["StudentID"]);
        echo json_encode($result);
    }

    // Add Question Rubrics
    public function addQuestionRubrics($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.question (RubricsNo, OrderNo, QuestionText, SurveyTypeID, StatusID, StudentOutcome_SOID, Course_CourseID) values ('
                . '?, '
                . '?, '
                . '?, '
                . '(select SurveyTypeID from ABET.SurveyType where SurveyName = ?), ' // SurveyType: CLO-Based
                . '(select StatusID from ABET.Status where StatusName = ?), ' // Active, Inactive
                . '(select SOID from ABET.StudentOutcome where SOCode = ?), ' // a-k
                . '(select CourseID from ABET.Course where CourseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?))'
                . ');';
        $result = $dao->query($query, $request->get('rubricsNo'), $request->get('orderNo'), $request->get('questionText'), $request->get('surveyType'), $request->get('statusName'), $request->get('SOCode'), $request->get('courseCode'), $request->get('pnameShort'));
        echo json_encode($result);
    }

    // Add Question 
    public function addQuestion($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.question (OrderNo, QuestionText, SurveyTypeID, StatusID, StudentOutcome_SOID, Course_CourseID) values ('
                . '?, '
                . '?, '
                . '(select SurveyTypeID from ABET.SurveyType where SurveyName = ? ), ' // SurveyType: CLO-Based
                . '(select StatusID from ABET.Status where StatusName = ? AND StatusType = ?), ' // Active, Inactive
                . '(select SOID from ABET.StudentOutcome where SOCode = ?), ' // a-k
                . '(select CourseID from ABET.Course where CourseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?))'
                . ');';
        $result = $dao->query($query, $request->get('orderNo'), $request->get('questionText'), $request->get('SurveyName'), $request->get('statusName'), $request->get('statusType'), $request->get('SOCode'), $request->get('courseCode'), $request->get('pnameShort'));
        echo json_encode($result);
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

    public function getAllCourses($request) {
        $dao = new ABETDAO();
        $query = 'SELECT PNameShort, CourseCode, SEM.SemesterNum, F.FacultyName '
                . 'FROM Faculty F, Section SEC, Semester SEM, Course C, Program P '
                . 'WHERE C.CourseID = SEC.CourseID '
                . 'AND Email = ? '
                . 'AND F.FacultyID = SEC.FacultyID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND SEM.SemesterID = SEC.SemesterID ';
        $rows = $dao->query($query, $request->get('email'));
        $courses = [];
        foreach ($rows as $row) {
            $courses[] = [
                "pnameShort" => $row["PNameShort"],
                "courseCode" => $row["CourseCode"],
                "Faculty" => $row["FacultyName"],
                "semester" => $row["SemesterNum"]
            ];
        }
        echo json_encode($courses);
    }

    public function getStatus($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Status where StatusType = ?;';
        $rows = $dao->query($query, $request->get("StatusType"));
        $faculty = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $faculty[] = [
                    "StatusName" => $row["StatusName"],
                    "StatusType" => $row["StatusType"],
                    "description" => $row["Description"],
                ];
            }
        }
        $encoded = json_encode($faculty);
        echo $encoded;
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
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ?;';
        $rows = $dao->query($query, $request->get('semester'), $request->get('courseCode'), $request->get('pnameShort'));

        $students = [];
        foreach ($rows as $row) {
            $students[] = [
                "SUID" => $row["SUID"],
                "STUName" => $row["StudentName"]
            ];
        }
        echo json_encode($students);
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

    function getCLOQuestions($request) {
        $dao = new ABETDAO();

        $query = 'SELECT * '
                . 'FROM ABET.QUESTION Q, ABET.COURSE C, ABET.PROGRAM P, ABET.StudentOutcome SO, ABET.SurveyType ST, ABET.STATUS STA '
                . 'WHERE Q.COURSE_COURSEID = C.COURSEID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND ST.SURVEYTYPEID = Q.SURVEYTYPEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? '
                . 'AND ST.SURVEYNAME = ? ' // CLO-Based
                . 'AND STA.STATUSID = ST.STATUSID '
                . 'AND STA.STATUSTYPE = ? ;'; // Survey
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'), 'CLO-Based', 'Survey');
        $questions = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $questions[] = [
                    "question" => $row["QuestionText"],
                    "order" => $row["OrderNo"],
                    "SOCode" => $row["SOCode"],
                    "status" => $row["StatusName"],
                    "survey" => $row["SurveyName"]
                ];
            }
        }
        echo json_encode($questions);
    }

    function getRubQuestions($request) {
        $dao = new ABETDAO();

        $query = 'SELECT * '
                . 'FROM ABET.QUESTION Q, ABET.COURSE C, ABET.PROGRAM P, ABET.StudentOutcome SO, ABET.SurveyType ST, ABET.STATUS STA '
                . 'WHERE Q.COURSE_COURSEID = C.COURSEID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND ST.SURVEYTYPEID = Q.SURVEYTYPEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? '
                . 'AND ST.SURVEYNAME = ? ' // CLO-Based
                . 'AND STA.STATUSID = ST.STATUSID '
                . 'AND STA.STATUSTYPE = ? ;'; // Survey
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'), 'Rubrics-Based', 'Survey');
        $questions = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $questions[] = [
                    "question" => $row["QuestionText"],
                    "order" => $row["OrderNo"],
                    "SOCode" => $row["SOCode"],
                    "status" => $row["StatusName"],
                    "survey" => $row["SurveyName"]
                ];
            }
        }
        echo json_encode($questions);
    }

    function getExitQuestions($request) {
        $dao = new ABETDAO();

        $query = 'SELECT * '
                . 'FROM ABET.QUESTION Q, ABET.COURSE C, ABET.PROGRAM P, ABET.StudentOutcome SO, ABET.SurveyType ST, ABET.STATUS STA '
                . 'WHERE Q.COURSE_COURSEID = C.COURSEID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND ST.SURVEYTYPEID = Q.SURVEYTYPEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? '
                . 'AND ST.SURVEYNAME = ? ' // CLO-Based
                . 'AND STA.STATUSID = ST.STATUSID '
                . 'AND STA.STATUSTYPE = ? ;'; // Survey
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'), 'Exit-Based', 'Survey');
        $questions = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $questions[] = [
                    "question" => $row["QuestionText"],
                    "order" => $row["OrderNo"],
                    "SOCode" => $row["SOCode"],
                    "status" => $row["StatusName"],
                    "survey" => $row["SurveyName"]
                ];
            }
        }
        echo json_encode($questions);
    }

    function getStudentAnswers($request) {
        $dao = new ABETDAO();
        $query = '';
        $rows = $dao->query($query);
        $studentAnswers = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $studentAnswers[] = [
                    "SUID" => $row["SUID"],
                    "Question1Answer" //
                ];
            }
        }
        echo json_encode($studentAnswers);
    }

    function addStudentAnswers($request) {
        $dao = new ABETDAO();
        $query = '';
        $rows = $dao->query($query);
        echo json_encode($rows);
    }

    function deleteStudentAnswers($request) {
        $dao = new ABETDAO();
        $query = '';
        $rows = $dao->query($query);
        echo json_encode($rows);
    }

    function getSummary($request) {
        $dao = new ABETDAO();
        $query = '';
        $rows = $dao->query($query);
        $summary = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $summary[] = [
                    "SUID" => $row["SUID"],
                    "Question1Answer" //
                ];
            }
        }
        echo json_encode($summary);
    }

    function deleteQuestion($request) {
        $dao = new ABETDAO();
        $query = 'delete from Question where QuestionText = ? and Course_CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) ;';
        $rows = $dao->query($query, $request->get('questionText'), $request->get('courseCode'), $request->get('pnameShort'));
        echo json_encode($rows);
    }

    /// QA 
    public function getCLOQA($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * '
                . 'FROM ABET.Question Q, ABET.Answer A, ABET.QA QA, ABET.Course C, ABET.Program P, ABET.Status STA, ABET.SurveyType ST '
                . 'WHERE QA.Answer_AID = A.AID '
                . 'AND QA.Question_QID = Q.QID '
                . 'AND Q.COURSE_COURSEID = C.COURSEID '
                . 'AND Q.SURVEYTYPEID = ST.SURVEYTYPEID '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? '
                . ' '
                . 'AND ST.SURVEYNAME = \'CLO-Based\''
                . 'ORDER BY QID, WEIGHT_VALUE ;';
        // AND STA.STATUSNAME = \'Valid\' 
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'));
        $questions = [];
        foreach ($rows as $row) {
            $questions[] = [
                "Question" => $row["QuestionText"],
                "weightName" => $row["Weight_Name"],
                "weightValue" => $row["Weight_Name"]
            ];
        }
        echo json_encode($rows);
    }

    function getSO($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM ABET.StudentOutcome;';

        $rows = $dao->query($query);
        $sos = [];
        foreach ($rows as $row) {
            $sos[] = [
                "SOCode" => $row["SOCode"],
                "Description" => $row["Description"]
            ];
        }
        echo json_encode($sos);
    }

    function display($request) {
        //echo 'Hello <br>';
        // $request->get('rubricsNo'), $request->get('orderNo'), $request->get('questionText'), $request->get('surveyType'), $request->get('statusName'),$request->get('SOCode'), $request->get('courseCode'), $request->get('pnameShort'));
        //$request->set("rubricsNo", '152');
        // $dao->query($query, $request->get('orderNo'), $request->get('questionText'), $request->get('SurveyName'), $request->get('statusName'), $request->get('statusType'), $request->get('SOCode'), $request->get('courseCode'), $request->get('pnameShort'));
        $request->set("orderNo", '6');
        $request->set("questionText", 'Hello, Test3');
        $request->set("statusType", 'Survey');
        $request->set("statusName", 'Active');
        $request->set("SurveyName", 'Rubrics-Based');
        $request->set("pnameShort", 'ICS');
        $request->set("courseCode", '102');
        $request->set("SOCode", 'a');


        $this->addQuestion($request);
    }

}
