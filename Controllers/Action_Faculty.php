<?php

require_once 'ABETDAO.php';

//require_once '../Models/Term.php';

class Action_Faculty {

    public function addQA($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.QA (Question_QID, Answer_AID, Status_StatusID) values (' .
                ' (select QID from Abet.Question where QuestionText = \'' . $request->get('questionText') . '\'), '
                . '(select AID from ABET.Answer where weight_name = \'' . $request->get('weightName') . '\'),'
                . '(select StatusID from abet.status where StatusType = \'' . $request->get('statusType') . '\' and statusname = \'' . $request->get('statusName') . '\')'
                . ');';
        $dao->excuteQuery($query);
    }

    public function addComment($request) {
        $dao = new ABETDAO();
        $query1 = 'select SectionID from Section where SectionNum = ? AND CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND FacultyID = (select FacultyID from Faculty where email = ?)';
        $result1 = $dao->query($query1, $request->get('sectionNum'), $request->get('courseCode'), $request->get('pname'), $request->get('email'));
        // echo json_encode($result1) . '<br />';
        // 
        $query2 = 'select QID from Question where QuestionText = ? AND Course_CourseID = (select CourseID from Course where CourseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND SurveyTypeID = (Select SurveyTypeID from SurveyType where SurveyName = ?)';
        $result2 = $dao->query($query2, $request->get('questionText'), $request->get('courseCode'), $request->get('pname'), $request->get('surveyName'));
        //echo json_encode($result2) . '<br />';
        $query = 'insert into Comment (Weakness, Actions, Section_SectionID, Question_QID) VALUES ('
                . '?, '
                . '?, '
                . '?, '
                . '? '
                . ')';
        $result = $dao->query($query, $request->get('weakness'), $request->get('actions'), $result1[0]['SectionID'], $result2[0]['QID']);
        echo json_encode($result);
    }

    public function getComments($request) {
        $dao = new ABETDAO();
        $query = 'select Weakness, Actions, QuestionText from Comment C, Question Q '
                . 'where Q.QID = C.Question_QID '
                . 'AND Q.SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)'
                . 'AND C.Section_SectionID = (select SectionID from Section where SectionNum = ? AND CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND FacultyID = (select FacultyID from Faculty where email = ?));';
        $rows = $dao->query($query, $request->get('surveyName'), $request->get('sectionNum'), $request->get('courseCode'), $request->get('pname'), $request->get('email'));
        echo json_encode($rows);
    }

    public function deleteComment($request) {
        $dao = new ABETDAO();
        $query1 = 'select SectionID from Section where SectionNum = ? AND CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND FacultyID = (select FacultyID from Faculty where email = ?)';
        $result1 = $dao->query($query1, $request->get('sectionNum'), $request->get('courseCode'), $request->get('pname'), $request->get('email'));
        $query2 = 'select QID from Question where QuestionText = ? AND Course_CourseID = (select CourseID from Course where CourseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND SurveyTypeID = (Select SurveyTypeID from SurveyType where SurveyName = ?)';
        $result2 = $dao->query($query2, $request->get('questionText'), $request->get('courseCode'), $request->get('pname'), $request->get('surveyName'));
        $query = 'delete from Comment where Weakness = ? AND Actions = ? AND Section_SectionID = ? AND Question_QID = ?';
        $result = $dao->query($query, $request->get("weakness"), $request->get("actions"), $result1[0]['SectionID'], $result2[0]['QID']);
        echo json_encode($result);
    }

    function updateComment($request) {
        $dao = new ABETDAO();
        $query1 = 'select SectionID from Section where SectionNum = ? AND CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND FacultyID = (select FacultyID from Faculty where email = ?)';
        $result1 = $dao->query($query1, $request->get('sectionNum'), $request->get('courseCode'), $request->get('pname'), $request->get('email'));
        $query2 = 'select QID from Question where QuestionText = ? AND Course_CourseID = (select CourseID from Course where CourseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND SurveyTypeID = (Select SurveyTypeID from SurveyType where SurveyName = ?)';
        $result2 = $dao->query($query2, $request->get('questionText'), $request->get('courseCode'), $request->get('pname'), $request->get('surveyName'));
        $query = 'update Comment set weakness = ?, actions = ? where Weakness = ? AND Actions = ? AND Section_SectionID = ? AND Question_QID = ?';
        $result = $dao->query($query, $request->get("newWeakness"), $request->get("newActions"), $request->get("weakness"), $request->get("actions"), $result1[0]['SectionID'], $result2[0]['QID']);
        echo json_encode($result);
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
        $query = 'insert into abet.question (pcnum, OrderNo, QuestionText, SurveyTypeID, StatusID, StudentOutcome_SOID, Course_CourseID) values ('
                . '?, '
                . '?, '
                . '?, '
                . '(select SurveyTypeID from ABET.SurveyType where SurveyName = ?), ' // SurveyType: CLO-Based
                . '(select StatusID from ABET.Status where StatusName = ? and StatusType = ?), ' // Active, Inactive
                . '(select SOID from ABET.StudentOutcome where SOCode = ?), ' // a-k
                . '(select CourseID from ABET.Course where CourseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?))'
                . ');';
        $result = $dao->query($query, $request->get('rubricsNo'), $request->get('orderNo'), $request->get('questionText'), $request->get('surveyType'), $request->get('statusName'), $request->get('statusType'), $request->get('SOCode'), $request->get('courseCode'), $request->get('pnameShort'));
        //echo json_encode($result);
        $query2 = 'select AID from Answer where SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) '
                . 'AND Status_StatusID = (select StatusID from ABET.Status where StatusName = ? AND StatusType = ?)';
        $aids = $dao->query($query2, $request->get('surveyType'), $request->get('statusName'), 'Survey');

        $query3 = 'select QID from Question where QuestionText = ? AND Course_CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)';
        $qid = $dao->query($query3, $request->get('questionText'), $request->get('courseCode'), $request->get('pnameShort'), $request->get('surveyType'));
        //echo json_encode($qid);
        for ($i = 0; $i < count($aids); $i++) {
            $query1 = 'insert into QA (Question_QID, Answer_AID, Status_StatusID) VALUES ('
                    . '(?),(?), (select StatusID from ABET.Status where StatusName = ? AND StatusType = ?));';
            $result2 = $dao->query($query1, $qid[0]["QID"], $aids[$i]["AID"], $request->get('statusName'), $request->get('statusType'));
            //echo json_encode($result2);
        }
        echo json_encode($aids);
        //echo json_encode($result);
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
        $query2 = 'select AID from Answer where SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) '
                . 'AND Status_StatusID = (select StatusID from ABET.Status where StatusName = ? AND StatusType = ?)';
        $aids = $dao->query($query2, $request->get('SurveyName'), $request->get('statusName'), $request->get('statusType'));
        $query3 = 'select QID from Question where QuestionText = ? AND Course_CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)';
        $qid = $dao->query($query3, $request->get('questionText'), $request->get('courseCode'), $request->get('pnameShort'), $request->get('SurveyName'));
        for ($i = 0; $i < count($aids); $i++) {
            $query1 = 'insert into QA (Question_QID, Answer_AID, Status_StatusID) VALUES ('
                    . '(?),(?), (select StatusID from ABET.Status where StatusName = ? AND StatusType = ?));';
            $dao->query($query1, $qid[0]["QID"], $aids[$i]["AID"], $request->get('statusName'), $request->get('statusType'));
        }
        /* $query1 = 'insert into QA (Question_QID, Answer_AID, Status_StatusID) VALUES ('
          . '(select QID from Question where QuestionText = ? AND Course_CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) AND SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)),'
          . '(select AID from Answer where SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)),'
          . '(select StatusID from ABET.Status where StatusName = ? AND StatusType = ?));';
          $result1 = $dao->query($query1, $request->get('questionText'), $request->get('courseCode'), $request->get('pnameShort'), $request->get('SurveyName'), $request->get('SurveyName'), $request->get('statusName'));
         * 
         */
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
        $query = 'SELECT PNameShort, CourseCode, SEM.SemesterNum, F.FacultyName, SEC.SectionNum '
                . 'FROM Faculty F, Section SEC, Semester SEM, Course C, Program P '
                . 'WHERE C.CourseID = SEC.CourseID '
                . 'AND Email = ? '
                . 'AND F.FacultyID = SEC.FacultyID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND SEM.SemesterID = SEC.SemesterID order by semesternum, pnameshort, coursecode, sectionnum';
        $rows = $dao->query($query, $request->get('email'));
        $courses = [];
        foreach ($rows as $row) {
            $courses[] = [
                "pnameShort" => $row["PNameShort"],
                "courseCode" => $row["CourseCode"],
                "Faculty" => $row["FacultyName"],
                "semester" => $row["SemesterNum"],
                "section" => $row["SectionNum"]
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

    function getEmpQuestions($request) {
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
        $rows = $dao->query($query, $request->get('courseCode'), $request->get('pnameShort'), 'Employer-Based', 'Survey');
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
        //$query = "select weight_name from abet.answer natural join abet.program where pnameshort = ?";
        $query1 = "select questiontext from program P, course C, question Q, SurveyType S "
                . "where  P.ProgramID = C.ProgramID AND C.CourseID = Q.Course_CourseID AND Q.SurveyTypeID = S.SurveyTypeID "
                . "AND pnameshort = ? AND coursecode = ? AND SurveyName = ?";
        $rows1 = $dao->query($query1, $request->get("pname"), $request->get("courseCode"), $request->get("surveyType")); //'CLO-Based');
        // $data = [];
        $columns = "";
        // echo json_encode($rows);
        $i = 0;
        for ($i = 0; $i < count($rows1); $i++) {
            $columns = $columns . "MAX(IF(QuestionText = '" . $rows1[$i]["questiontext"] . "', Weight_Name, NULL)) AS Question_" . ($i + 1) . ""; //$row["questiontext"];
            if ($i < count($rows1) - 1) {
                $columns = $columns . ", ";
            }
        }

        $query2 = 'SELECT SUID, ' . ($columns) . ' FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, 
            ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO, ABET.SurveyType ST, ABET.Course C, ABET.Program P 
            WHERE SEM.SEMESTERNUM = ? AND SEM.SEMESTERID = SEC.SEMESTERID AND SEC.SECTIONID = SS.SECTIONID AND SS.STUDENT_STUDENTID = STU.STUDENTID 
            AND SS.SSID = SQA.STUDENT_SECTION_SSID AND SQA.QA_QAID = QA.QAID AND QA.Answer_AID = A.AID AND QA.Question_QID = Q.QID 
            AND Q.STUDENTOUTCOME_SOID = SO.SOID AND Q.SURVEYTYPEID = ST.SURVEYTYPEID AND ST.SURVEYNAME = ? 
            AND Q.COURSE_COURSEID = C.COURSEID AND C.PROGRAMID = P.PROGRAMID AND P.PNAMESHORT = ? AND C.COURSECODE = ? GROUP BY SUID;';
        $rows = $dao->query($query2, $request->get('semester'), $request->get("surveyType"), $request->get('pname'), $request->get('courseCode'));
        $studentAnswers = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $answers = [];
                for ($j = 0; $j < count($row) - 1; $j++) {
                    $answers[] = $row["Question_" . ($j + 1)];
                }
                $studentAnswers[] = [
                    "SUID" => $row["SUID"],
                    "count" => count($rows1),
                    "answers" => $answers,
                ];
            }
        }
        print (json_encode($studentAnswers, JSON_PRETTY_PRINT));
    }

    public function getPCSummary($request) {
        $dao = new ABETDAO();
        $query = "SELECT GROUP_CONCAT(DISTINCT CONCAT('count(IF(weight_value = ''',Weight_value,''', Weight_Value, NULL)) AS \'',Weight_Name, '\'') order by weight_value desc) 
                    FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, 
                    ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO, 
                    ABET.SurveyType ST, ABET.Course C, ABET.Program P, ABET.Faculty F 
                    WHERE SEM.SEMESTERNUM = ?
                    AND SEC.FACULTYID = F.FACULTYID
                    AND F.EMAIL = ?
                    AND SEM.SEMESTERID = SEC.SEMESTERID
                    AND SEC.SECTIONID = SS.SECTIONID
                    AND SS.STUDENT_STUDENTID = STU.STUDENTID
                    AND SS.SSID = SQA.STUDENT_SECTION_SSID
                    AND SQA.QA_QAID = QA.QAID
                    AND QA.Answer_AID = A.AID
                    AND QA.Question_QID = Q.QID
                    AND Q.STUDENTOUTCOME_SOID = SO.SOID
                    AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
                    AND ST.SURVEYNAME = ?  
                    AND Q.COURSE_COURSEID = C.COURSEID
                    AND C.PROGRAMID = P.PROGRAMID
                    AND P.PNAMESHORT = ? 
                    AND C.COURSECODE = ?;";
        $result = $dao->query($query, $request->get("semester"), $request->get("femail"), $request->get("surveyType"), $request->get("pname"), $request->get("courseCode"));
        //echo json_encode($result);
        $answerStr = implode(" ", $result[0]);
        //echo json_encode($answerStr);
        $query2 = "SELECT  socode, Q.pcnum, Q.questiontext, " . $answerStr . ", avg(weight_value) as avg, (100*sum(if(a.weight_value > ?, 1, 0)) / count(*)) as percent FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, 
            ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO,
            ABET.SurveyType ST, ABET.Course C, ABET.Program P
            WHERE SEM.SEMESTERNUM = ?
            AND SEM.SEMESTERID = SEC.SEMESTERID
            AND SEC.SECTIONID = SS.SECTIONID
            AND SS.STUDENT_STUDENTID = STU.STUDENTID
            AND SS.SSID = SQA.STUDENT_SECTION_SSID
            AND SQA.QA_QAID = QA.QAID
            AND QA.Answer_AID = A.AID
            AND QA.Question_QID = Q.QID
            AND Q.STUDENTOUTCOME_SOID = SO.SOID
            AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
            AND ST.SURVEYNAME = ?
            AND Q.COURSE_COURSEID = C.COURSEID
            AND C.PROGRAMID = P.PROGRAMID
            AND P.PNAMESHORT = ?
            AND C.COURSECODE = ? GROUP BY ( Q.questiontext);";
        $result2 = $dao->query($query2, $request->get("value"), $request->get("semester"), $request->get("surveyType"), $request->get("pname"), $request->get("courseCode"));
        //echo json_encode($result2);
        $tmpQuery = "select DISTINCT Weight_Name from answer a, program p where a.program_programid = p.programid and p.pnameshort = ? and a.SurveyType_SurveyTypeID = (SELECT surveytypeid from surveytype WHERE surveyname = 'Rubrics-Based')";
        $tmpRows = $dao->query($tmpQuery, $request->get('pname'));
        $answerNames = [];
        for ($j = 0; $j < count($tmpRows); $j++) {
            $answerNames[$j] = $tmpRows[$j]["Weight_Name"];
        }
        $summary = [];
        if ($result2 != false) {
            foreach ($result2 as $row) {
                $answerCount = [];
                for ($i = 0; $i < count($answerNames); $i++) {
                    $tmpStr = str_replace(" ", "_", $answerNames[$i]);
                    if (isset($row[$tmpStr])) {
                        $answerCount[$i] = $row[$tmpStr];
                    } else {
                        $answerCount[$i] = 0;
                    }
                }
                $summary[] = [
                    "pcnum" => $row["pcnum"],
                    "Question" => $row["questiontext"],
                    "SOCode" => $row["socode"],
                    "avg" => $row["avg"],
                    "percent" => $row["percent"],
                    "answersValues" => $answerCount,
                    "answerNames" => $answerNames,
                    "NumberAnswers" => count($answerNames),
                ];
            }
        }
        echo json_encode($summary);
    }

    function getPCResponse($request) {
        $dao = new ABETDAO();
        //$query = "select weight_name from abet.answer natural join abet.program where pnameshort = ?";
        $query1 = "SELECT GROUP_CONCAT(DISTINCT CONCAT('count(IF(weight_value = ''', Weight_value,''', Weight_Value, NULL)) AS Weight_Name') 		
  )             FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU,
                ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO,
                ABET.SurveyType ST, ABET.Course C, ABET.Program P, ABET.Faculty F
                WHERE SEM.SEMESTERNUM = ?
                AND SEC.FACULTYID = F.FACULTYID
                AND F.EMAIL = ?
                AND SEM.SEMESTERID = SEC.SEMESTERID
                AND SEC.SECTIONID = SS.SECTIONID
                AND SS.STUDENT_STUDENTID = STU.STUDENTID
                AND SS.SSID = SQA.STUDENT_SECTION_SSID
                AND SQA.QA_QAID = QA.QAID
                AND QA.Answer_AID = A.AID
                AND QA.Question_QID = Q.QID
                AND Q.STUDENTOUTCOME_SOID = SO.SOID
                AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
                AND ST.SURVEYNAME = ? 
                AND Q.COURSE_COURSEID = C.COURSEID
                AND C.PROGRAMID = P.PROGRAMID
                AND P.PNAMESHORT = ?
                AND C.COURSECODE = ?
                order by weight_value desc;";
        $rows1 = $dao->query($query1, $request->get("semester"), $request->get("femail"), $request->get("surveyType"), $request->get("pname"), $request->get("courseCode")); //'CLO-Based');
        // $data = [];
        $columns = "";
        //echo json_encode($rows1);
        $i = 0;
        for ($i = 0; $i < count($rows1); $i++) {
            $columns = $columns . "MAX(IF(QuestionText = '" . $rows1[$i]["questiontext"] . "', Weight_Name, NULL)) AS Question_" . ($i + 1) . ""; //$row["questiontext"];
            if ($i < count($rows1) - 1) {
                $columns = $columns . ", ";
            }
        }

        $query2 = 'SELECT  socode, Q.pcnum,Q.questiontext , ' . ($columns) . ' avg(weight_value), 100*sum(if(a.weight_value > ?, 1, 0)) / count(*) 
            FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO,
            ABET.SurveyType ST, ABET.Course C, ABET.Program P 
            WHERE SEM.SEMESTERNUM = ?
            AND SEM.SEMESTERID = SEC.SEMESTERID
            AND SEC.SECTIONID = SS.SECTIONID
            AND SS.STUDENT_STUDENTID = STU.STUDENTID
            AND SS.SSID = SQA.STUDENT_SECTION_SSID
            AND SQA.QA_QAID = QA.QAID
            AND QA.Answer_AID = A.AID
            AND QA.Question_QID = Q.QID
            AND Q.STUDENTOUTCOME_SOID = SO.SOID
            AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
            AND ST.SURVEYNAME = ?
            AND Q.COURSE_COURSEID = C.COURSEID
            AND C.PROGRAMID = P.PROGRAMID
            AND P.PNAMESHORT = ?
            AND C.COURSECODE = ? GROUP BY Q.questiontext;';
        $rows = $dao->query($query2, $request->get('value'), $request->get('semester'), $request->get("surveyName"), $request->get('pname'), $request->get('courseCode'));
        $studentAnswers = [];
        /*
          if ($rows != false) {
          foreach ($rows as $row) {
          $answers = [];
          for ($j = 0; $j < count($row) - 1; $j++) {
          $answers[] = $row["Question_" . ($j + 1)];
          }
          $studentAnswers[] = [
          "SUID" => $row["SUID"],
          "count" => count($rows1),
          "answers" => $answers,
          ];
          }
          }
         * */

        print (json_encode($studentAnswers, JSON_PRETTY_PRINT));
        /*
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
         * 
         */
    }

    function addStudentAnswers($request) {
        $dao = new ABETDAO();
        $query = "INSERT INTO ABET.STUDENTQA (STUDENT_SECTION_SSID, QA_QAID) VALUES (
                (?),
                (SELECT QAID FROM ABET.QA QA, ABET.QUESTION Q, ABET.ANSWER A, ABET.SURVEYTYPE 	SUT,  ABET.COURSE C, ABET.PROGRAM P
                WHERE QA.QUESTION_QID = Q.QID
                AND QA.ANSWER_AID = A.AID
                AND Q.COURSE_COURSEID = C.COURSEID
                AND C.PROGRAMID = P.PROGRAMID
                AND A.Program_ProgramID = P.ProgramID
                AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID
                AND SUT.SURVEYNAME = ?
                AND C.COURSECODE = ?
                AND P.PNAMESHORT = ?
                AND A.weight_value = ?
                AND Q.QuestionText = ?)
                );";
        $rows = '';
        $tmpQuery = 'SELECT SS.SSID 
                FROM ABET.PROGRAM P, ABET.COURSE C, ABET.SECTION SEC, ABET.STUDENT STU, ABET.STUDENT_SECTION SS 
                WHERE P.PROGRAMID = C.PROGRAMID 
                AND C.COURSEID = SEC.COURSEID 
                AND SEC.SECTIONID = SS.SECTIONID 
                AND SS.STUDENT_STUDENTID = STU.STUDENTID 
                AND STU.SUID = ? 
                AND C.COURSECODE = ? 
                AND P.PNAMESHORT = ? 
                AND SEC.SectionNum = ?';
        $tmpRows = $dao->query($tmpQuery, $request->get("ID"), $request->get("courseCode"), $request->get("pname"), $request->get("section"));
        for ($i = 0; $i < count($request->get("answers")); $i++) {
            $rows = $dao->query($query, $tmpRows[0]["SSID"], $request->get("surveyName"), $request->get("courseCode"), $request->get("pname"), $request->get("answers")[$i], $request->get("questions")[$i]);
        }
        echo json_encode($rows);
        if ($request->get('surveyName') == 'CLO-Based') {
            $query1 = 'UPDATE student_section SET isCLOFilled = 1 '
                    . 'WHERE Student_StudentID = (select StudentID from Student where SUID = ?) '
                    . 'AND SectionID = (select SectionID from Section where sectionNum = ? '
                    . 'AND CourseID = (select CourseID from Course where CourseCode = ? '
                    . 'AND ProgramID = (select ProgramID from Program where PNameShort = ?)));';
            $rows = $dao->query($query1, $request->get("ID"), $request->get("section"), $request->get("courseCode"), $request->get("pname"));
        }
        //echo json_encode($tmpRows);
    }

    function deleteStudentAnswers($request) {
        $dao = new ABETDAO();
        $query = "delete from ABET.STUDENTQA where STUDENT_SECTION_SSID = ?
                AND QA_QAID = (
                SELECT QAID FROM ABET.QA QA, ABET.QUESTION Q, ABET.ANSWER A, ABET.SURVEYTYPE SUT, ABET.COURSE C, ABET.PROGRAM P
                WHERE QA.QUESTION_QID = Q.QID
                AND QA.ANSWER_AID = A.AID
                AND Q.COURSE_COURSEID = C.COURSEID
                AND C.PROGRAMID = P.PROGRAMID
                AND A.Program_ProgramID = P.ProgramID
                AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID
                AND SUT.SURVEYNAME = ?
                AND C.COURSECODE = ?
                AND P.PNAMESHORT = ?
                AND A.weight_name = ?
                AND Q.QuestionText = ?
                );";
        $rows = '';
        $tmpQuery = 'SELECT SS.SSID 
                FROM ABET.PROGRAM P, ABET.COURSE C, ABET.SECTION SEC, ABET.STUDENT STU, ABET.STUDENT_SECTION SS 
                WHERE P.PROGRAMID = C.PROGRAMID 
                AND C.COURSEID = SEC.COURSEID 
                AND SEC.SECTIONID = SS.SECTIONID 
                AND SS.STUDENT_STUDENTID = STU.STUDENTID 
                AND STU.SUID = ? 
                AND C.COURSECODE = ? 
                AND P.PNAMESHORT = ? 
                AND SEC.SectionNum = ?';
        $tmpRows = $dao->query($tmpQuery, $request->get("ID"), $request->get("courseCode"), $request->get("pname"), $request->get("section"));
        for ($i = 0; $i < count($request->get("answers")); $i++) {
            $rows = $dao->query($query, $tmpRows[0]["SSID"], $request->get('surveyName'), $request->get("courseCode"), $request->get("pname"), $request->get("answers")[$i], $request->get("questions")[$i]);
        }
        //$rows = $dao->query($query);
        if ($request->get('surveyName') == "CLO-Based") {
            $query1 = 'UPDATE student_section SET isCLOFilled = 0 '
                    . 'WHERE Student_StudentID = (select StudentID from Student where SUID = ?) '
                    . 'AND SectionID = (select SectionID from Section where sectionNum = ? '
                    . 'AND CourseID = (select CourseID from Course where CourseCode = ? '
                    . 'AND ProgramID = (select ProgramID from Program where PNameShort = ?)));';
            $rows = $dao->query($query1, $request->get("ID"), $request->get("section"), $request->get("courseCode"), $request->get("pname"));
        }
        echo json_encode($tmpRows);
    }

    function getSummary($request) {
        $dao = new ABETDAO();
        $query = "SELECT GROUP_CONCAT(DISTINCT CONCAT('count(IF(weight_value = ''', Weight_value,''', Weight_Value, NULL)) AS ', replace(weight_name, ' ', '_')) order by weight_value desc) 
            FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU,
            ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO,
            ABET.SurveyType ST, ABET.Course C, ABET.Program P, ABET.Faculty F
            WHERE SEM.SEMESTERNUM = ?
            AND SEC.FACULTYID = F.FACULTYID
            AND F.EMAIL = ?
            AND SEM.SEMESTERID = SEC.SEMESTERID
            AND SEC.SECTIONID = SS.SECTIONID
            AND SS.STUDENT_STUDENTID = STU.STUDENTID
            AND SS.SSID = SQA.STUDENT_SECTION_SSID
            AND SQA.QA_QAID = QA.QAID
            AND QA.Answer_AID = A.AID
            AND QA.Question_QID = Q.QID
            AND Q.STUDENTOUTCOME_SOID = SO.SOID
            AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
            AND ST.SURVEYNAME = ? 
            AND Q.COURSE_COURSEID = C.COURSEID
            AND C.PROGRAMID = P.PROGRAMID
            AND P.PNAMESHORT = ?
            AND C.COURSECODE = ?;";
        $rows = $dao->query($query, $request->get('semester'), $request->get('femail'), $request->get('surveyName'), $request->get('pname'), $request->get('courseCode'));
        //echo json_encode($rows[0]);
        $answerStr = implode(" ", $rows[0]);
        $tmpQuery = 'select distinct weight_name from answer a, program p where a.program_programid = p.programid and p.pnameshort = ? and a.SurveyType_SurveyTypeID = (SELECT surveytypeid from surveytype WHERE surveyname = ?)';
        $tmpRows = $dao->query($tmpQuery, $request->get('pname'), $request->get('surveyName'));
        // echo json_encode($tmpRows);
        // echo '<br />' . $answerStr . '<br /> <br />';
        $query1 = "SELECT Q.orderno, socode, QuestionText, " . $answerStr . ", avg(weight_value) as avg FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, 
            ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO,
            ABET.SurveyType ST, ABET.Course C, ABET.Program P, ABET.faculty f
            WHERE SEM.SEMESTERNUM = ?
            and f.facultyid = sec.facultyid
            and f.email = ?
            AND SEM.SEMESTERID = SEC.SEMESTERID
            AND SEC.SECTIONID = SS.SECTIONID
            AND SS.STUDENT_STUDENTID = STU.STUDENTID
            AND SS.SSID = SQA.STUDENT_SECTION_SSID
            AND SQA.QA_QAID = QA.QAID
            AND QA.Answer_AID = A.AID
            AND QA.Question_QID = Q.QID
            AND Q.STUDENTOUTCOME_SOID = SO.SOID
            AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
            AND ST.SURVEYNAME = ?
            AND Q.COURSE_COURSEID = C.COURSEID
            AND C.PROGRAMID = P.PROGRAMID
            AND P.PNAMESHORT = ?
            AND C.COURSECODE = ? GROUP BY questiontext;";
        // CHANGE SURVEY NAME
        $rows1 = $dao->query($query1, $request->get('semester'), $request->get('femail'), $request->get('surveyName'), $request->get('pname'), $request->get('courseCode'));
        $answerNames = [];
        for ($j = 0; $j < count($tmpRows); $j++) {
            $answerNames[$j] = $tmpRows[$j]["weight_name"];
        }
        $summary = [];
        if ($rows1 != false) {
            foreach ($rows1 as $row) {
                $answerCount = [];
                for ($i = 0; $i < count($answerNames); $i++) {
                    $tmpStr = str_replace(" ", "_", $answerNames[$i]);
                    if (isset($row[$tmpStr])) {
                        $answerCount[$i] = $row[$tmpStr];
                    } else {
                        $answerCount[$i] = 0;
                    }
                }
                $summary[] = [
                    "Order" => $row["orderno"],
                    "Question" => $row["QuestionText"],
                    "SOCode" => $row["socode"],
                    "avg" => $row["avg"],
                    "answersValues" => $answerCount,
                    "answerNames" => $answerNames,
                    "NumberAnswers" => count($answerNames),
                ];
            }
        }

        // echo json_encode($rows1, JSON_NUMERIC_CHECK) . '<br /> <br />';

        echo json_encode($summary, JSON_PRETTY_PRINT);
    }

    function deleteQuestion($request) {
        $dao = new ABETDAO();
        $query = 'delete from Question where QuestionText = ? and Course_CourseID = (select CourseID from Course where courseCode = ? AND ProgramID = (select ProgramID from Program where PNameShort = ?)) ;';
        $rows = $dao->query($query, $request->get('questionText'), $request->get('courseCode'), $request->get('pnameShort'));
        echo json_encode($rows);
    }

    function updateQuestion($request) {
        $dao = new ABETDAO();
        // TO-DO Mustafa
        // Modify the following query to do update instead of deleteing
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

    function getDynamicAnswers($request) {
        $dao = new ABETDAO();
        $query = "select weight_name, weight_value from abet.answer a, abet.status sta, abet.surveytype st, abet.program p "
                . "where st.surveyname = ? and sta.statustype = ? and sta.statusname = ? and p.pnameshort = ? and p.programid = a.program_programid and a.surveytype_surveytypeid = st.surveytypeid and sta.statusid = a.status_statusid"
                . " order by weight_value desc;";
        $rows = $dao->query($query, $request->get('surveyName'), $request->get('statusName'), 'Active', $request->get('pname'));
        echo json_encode($rows);
    }

    function getDynamicQuestion($request) {
        $dao = new ABETDAO();
        $query = "select questiontext "
                . "from abet.question q, abet.course c, abet.program p, abet.status sta, abet.surveytype st "
                . " where q.course_courseid = c.courseid and c.programid = p.programid and sta.statusid = q.statusid and q.surveytypeid = st.surveytypeid "
                . "and c.coursecode = ? and p.pnameshort = ? and st.surveyname = ? and sta.statustype = ? and sta.statusname = ?;";
        $rows = $dao->query($query, $request->get("courseCode"), $request->get("pname"), $request->get("surveyName"), $request->get("statusName"), 'Active');
        echo json_encode($rows);
    }

    function display($request) {
        //echo 'Hello <br>';
        // $request->get('suid'), $request->get('semester'), $request->get('courseCode'), $request->get('pname'), $request->get('courseCode'), $request->get('pname'),  $request->get('weightAnswer'), $request->get('Question'));
        // $request->get('weakness'),$request->get('actions'), $request->get('sectionNum'), $request->get('courseCode'), $request->get('pname'), $request->get('email'), $request->get('questionText'), $request->get('courseCode'), $request->get('pname'),$request->get('SoCode'), $request->get('pname')
        // $request->get('suid'), $request->get('semester'), $request->get('femail'), $request->get('courseCode'), $request->get('pname'), $request->get('courseCode'), $request->get('pname'), $request->get('weightValue'), $request->get('Question')
        //$request->set("orderNo", '21');
        //$request->set("Question", 'Ability to Program');
        //$request->set("suid", '201154810');
        //$request->set("weakness", 'Everything');
        // $request->set("semester", '152');
        //$request->set("weightValue", '4');
//        $request->set('SOCode', 'a');
        $request->set('semester', '152');
        $request->set("statusType", 'Survey');
        $request->set("statusName", 'Active');
        $request->set("surveyName", 'CLO-Based');
        $request->set("pname", 'ICS');
        $request->set("courseCode", '102');
        $request->set("sectionNum", '1');
        $request->set("femail", 'saleh');

        $this->getSummary($request);
    }

}
