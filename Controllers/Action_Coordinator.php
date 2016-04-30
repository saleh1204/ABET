<?php

require_once 'ABETDAO.php';

class Action_Coordinator {

    public function addStudentOutcome($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.StudentOutcome (SOCode, StatusID, Description, Program_ProgramID, DateActivated, DateDeactivated) values (?, '
                . '(select StatusID from ABET.Status where StatusType = \'Student Outcome\' and StatusName = ?), ? '
                . ', (SELECT ProgramID FROM ABET.Program WHERE PNameShort =?), '
                . '?,'
                . '?);';
        $result = $dao->query($query, $request->get('SOCode'), $request->get('StatusName'), $request->get('description'), $request->get('pnameShort'), $request->get('dateActivated'), $request->get('dateDeactivated'));
        echo json_encode($result);
    }

    public function deleteStudentOutcome($request) {
        $dao = new ABETDAO();
        $query = 'delete from StudentOutcome where SOCode = ? AND Program_ProgramID = (select ProgramID from Program where PNameShort = ?);';
        $result = $dao->query($query, $request->get('SOCode'), $request->get('pnameShort'));
        echo json_encode($result);
    }

    public function updateStudentOutcome($request) {
        $dao = new ABETDAO();
        $query = 'update StudentOutcome set SOCode = ?, Description = ?, DateActivated = ?, DateDeactivated = ?, StatusID = (select StatusID from Status where StatusName = ? AND StatusType = \'Student Outcome\') where SOCode = ? AND Program_ProgramID = (select ProgramID from Program where PNameShort = ?);';
        $result = $dao->query($query, $request->get('SOCode'), $request->get('description'), $request->get('dateActivated'), $request->get('dateDeactivated'), $request->get('StatusName'), $request->get('oldSOCode'), $request->get('pnameShort'));
        echo json_encode($result);
    }

    function getSO($request) {
        $dao = new ABETDAO();
        $query = 'select SOCode, StatusName, StudentOutcome.Description, StudentOutcome.DateActivated, StudentOutcome.DateDeactivated from StudentOutcome JOIN Status ON StudentOutcome.StatusID = Status.StatusID where Program_ProgramID = (select ProgramID from Program where PNameShort = ?);';
        $rows = $dao->query($query, $request->get('PName'));
        $so = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $so[] = [
                    "SOCode" => $row["SOCode"],
                    "StatusName" => $row["StatusName"],
                    "Description" => $row["Description"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"]
                ];
            }
        }
        echo json_encode($so);
    }

    /*
      public function addCLOAnswer($request) {
      $dao = new ABETDAO();
      $query = 'INSERT INTO answer (Answer, Weight_Value, Weight_Name, DateActivated, DateDeactivated, SurveyType_SurveyTypeID, Status_StatusID, StudentOutcome_SOID, Program_ProgramID) '
      . 'VALUES (?, ?, ?, ?, ?, (select SurveyTypeID from SurveyType where SurveyName = ?), (select StatusID from Status where StatusType = \'Survey\' AND StatusName = ?), (select SOID from studentoutcome where SOCode = ?), (select ProgramID from Program where PNameShort = ?));';
      $result = $dao->query($query, $request->get('Answer'), $request->get('weightValue'), $request->get('weightName'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'CLO-Based', $request->get('statusName'), $request->get('SOCOde'), $request->get('pname'));
      echo json_encode($result);
      }
     */

    public function getCLOAnswers($request) {
        $dao = new ABETDAO();
        $query = 'select weight_Name, weight_Value, DateActivated, DateDeactivated, StatusName from answer A, Status S where Program_ProgramID = (select ProgramID from Program where PNameShort = ?) AND A.Status_StatusID = S.StatusID AND A.SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)';
        $rows = $dao->query($query, $request->get('pname'), 'CLO-Based');
        $answers = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $answers[] = [
                    "StatusName" => $row["StatusName"],
                    "weightName" => $row["weight_Name"],
                    "weightValue" => $row["weight_Value"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"]
                ];
            }
        }
        echo json_encode($answers);
    }

    public function addCLOAnswer($request) {
        $dao = new ABETDAO();
        $query = 'insert into answer (Weight_Value, Weight_Name, DateActivated, DateDeactivated, SurveyType_SurveyTypeID, Status_StatusID, Program_ProgramID) values '
                . '(?, ?, ?, ?, (select SurveyTypeID from SurveyType where SurveyName = ?), (select StatusID from Status where StatusType = "Survey" AND StatusName = ?), (select ProgramID from Program where PNameShort = ?))';
        $rows = $dao->query($query, $request->get('weightValue'), $request->get('weightName'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'CLO-Based', $request->get('statusName'), $request->get('pname'));
        echo json_encode($rows);
    }

    public function deleteCLOAnswer($request) {
        $dao = new ABETDAO();
        $query = 'delete from Answer where Weight_Name = ? AND Weight_Value = ? AND SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) AND Program_ProgramID = (select ProgramID from Program where PNameShort = ?)';
        $rows = $dao->query($query, $request->get('weightName'), $request->get('weightValue'), 'CLO-Based', $request->get('pname'));
        echo json_encode($rows);
    }

    public function getPCAnswers($request) {
        $dao = new ABETDAO();
        $query = 'select weight_Name, weight_Value, A.DateActivated, A.DateDeactivated, StatusName, PCNum, SOCode  from answer A, Status S, StudentOutcome SO where A.Program_ProgramID = (select ProgramID from Program where PNameShort = ?) AND A.Status_StatusID = S.StatusID AND A.SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) AND SO.SOID = A.StudentOutcome_SOID';
        $rows = $dao->query($query, $request->get('pname'), 'Rubrics-Based');
        $answers = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $answers[] = [
                    "PC" => $row["PCNum"],
                    "StatusName" => $row["StatusName"],
                    "weightName" => $row["weight_Name"],
                    "weightValue" => $row["weight_Value"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"]
                ];
            }
        }
        echo json_encode($answers);
    }

    public function addPCAnswer($request) {
        $dao = new ABETDAO();
        $query = 'insert into answer (Weight_Value, Weight_Name, DateActivated, DateDeactivated, SurveyType_SurveyTypeID, Status_StatusID, Program_ProgramID) values '
                . '(?, ?, ?, ?, (select SurveyTypeID from SurveyType where SurveyName = ?), (select StatusID from Status where StatusType = "Survey" AND StatusName = ?), (select ProgramID from Program where PNameShort = ?))';
        $rows = $dao->query($query, $request->get('weightValue'), $request->get('weightName'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'Rubrics-Based', $request->get('statusName'), $request->get('pname'));
        echo json_encode($rows);
    }

    public function deletePCAnswer($request) {
        $dao = new ABETDAO();
        $query = 'delete from Answer where Weight_Name = ? AND Weight_Value = ? AND SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) AND Program_ProgramID = (select ProgramID from Program where PNameShort = ?)';
        $rows = $dao->query($query, $request->get('weightName'), $request->get('weightValue'), 'Rubrics-Based', $request->get('pname'));
        echo json_encode($rows);
    }

    public function getExitAnswers($request) {
        $dao = new ABETDAO();
        $query = 'select weight_Name, weight_Value, DateActivated, DateDeactivated, StatusName from answer A, Status S where Program_ProgramID = (select ProgramID from Program where PNameShort = ?) AND A.Status_StatusID = S.StatusID AND A.SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)';
        $rows = $dao->query($query, $request->get('pname'), 'Exit-Based');
        $answers = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $answers[] = [
                    "StatusName" => $row["StatusName"],
                    "weightName" => $row["weight_Name"],
                    "weightValue" => $row["weight_Value"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"]
                ];
            }
        }
        echo json_encode($answers);
    }

    public function addExitAnswer($request) {
        $dao = new ABETDAO();
        $query = 'insert into answer (Weight_Value, Weight_Name, DateActivated, DateDeactivated, SurveyType_SurveyTypeID, Status_StatusID, Program_ProgramID) values '
                . '(?, ?, ?, ?, (select SurveyTypeID from SurveyType where SurveyName = ?), (select StatusID from Status where StatusType = "Survey" AND StatusName = ?), (select ProgramID from Program where PNameShort = ?))';
        $rows = $dao->query($query, $request->get('weightValue'), $request->get('weightName'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'Exit-Based', $request->get('statusName'), $request->get('pname'));
        echo json_encode($rows);
    }

    public function deleteExitAnswer($request) {
        $dao = new ABETDAO();
        $query = 'delete from Answer where Weight_Name = ? AND Weight_Value = ? AND SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) AND Program_ProgramID = (select ProgramID from Program where PNameShort = ?)';
        $rows = $dao->query($query, $request->get('weightName'), $request->get('weightValue'), 'Exit-Based', $request->get('pname'));
        echo json_encode($rows);
    }

    public function getEmpAnswers($request) {
        $dao = new ABETDAO();
        $query = 'select weight_Name, weight_Value, DateActivated, DateDeactivated, StatusName from answer A, Status S where Program_ProgramID = (select ProgramID from Program where PNameShort = ?) AND A.Status_StatusID = S.StatusID AND A.SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?)';
        $rows = $dao->query($query, $request->get('pname'), 'Employer-Based');
        $answers = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $answers[] = [
                    "StatusName" => $row["StatusName"],
                    "weightName" => $row["weight_Name"],
                    "weightValue" => $row["weight_Value"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"]
                ];
            }
        }
        echo json_encode($answers);
    }

    public function addEmpAnswer($request) {
        $dao = new ABETDAO();
        $query = 'insert into answer (Weight_Value, Weight_Name, DateActivated, DateDeactivated, SurveyType_SurveyTypeID, Status_StatusID, Program_ProgramID) values '
                . '(?, ?, ?, ?, (select SurveyTypeID from SurveyType where SurveyName = ?), (select StatusID from Status where StatusType = "Survey" AND StatusName = ?), (select ProgramID from Program where PNameShort = ?))';
        $rows = $dao->query($query, $request->get('weightValue'), $request->get('weightName'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'Employer-Based', $request->get('statusName'), $request->get('pname'));
        echo json_encode($rows);
    }

    public function deleteEmpAnswer($request) {
        $dao = new ABETDAO();
        $query = 'delete from Answer where Weight_Name = ? AND Weight_Value = ? AND SurveyType_SurveyTypeID = (select SurveyTypeID from SurveyType where SurveyName = ?) AND Program_ProgramID = (select ProgramID from Program where PNameShort = ?)';
        $rows = $dao->query($query, $request->get('weightName'), $request->get('weightValue'), 'Employer-Based', $request->get('pname'));
        echo json_encode($rows);
    }

    public function getRubrics($request) {
        $dao = new ABETDAO();
        $query = 'SELECT SOID, SOCODE FROM ABET.STUDENTOUTOCME NATURAL JOIN ABET.PROGRAM;';
        $rows = $dao->query($query);
        $rubrics = [];
        foreach ($rows as $row) {
            $rubrics[] = [
                "SOID" => $row["SOID"],
                "SOCode" => $row["SOCODE"]
            ];
        }
        return json_encode($rubrics);
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
                "Order" => $row["OrderNo"],
                "QuestionText" => $row["QuestionText"],
                "SOCode" => $row["SOCODE"],
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
                "RubricsNo" => $row["RUBRICSNO"]
            ];
        }
        return json_encode($questions);
    }

    public function getAnswerWeights($request) {
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
        $rows = $dao->query($query);
        $weights = [];
        foreach ($rows as $row) {
            $weights[] = [
                "weightName" => $row["Weight_Name"]
            ];
        }
        return json_encode($weights);
    }

    public function getAverageSOs($request) {
        // -- get the average for each SO in a given course for each question, grouped by CLO
        $dao = new ABETDAO();
        $query = 'SELECT SOCode, QuestionText , AVG(Weight_Value) '
                . 'FROM ABET.StudentOutcome SO, ABET.Question Q, ABET.Course C, ABET.SurveyType ST, ABET.QA QA, ABET.Answer A, ABET.StudentQA SQA, ABET.Program P'
                . 'WHERE SQA.QA_QAID = QA.QAID '
                . 'AND QA.Answer_AID = A.AID '
                . 'AND QA.Question_QID = Q.QID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND Q.SURVEYTYPEID = ST.SURVEYTYPEID '
                . 'AND ST.SURVEYNAME = = \'CLO-Based\' '
                . 'AND Q.COURSE_COURSEID = C.COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID'
                . 'AND P.PNAMESHORT = ? '
                . 'AND C.COURSECODE = ? '
                . 'GROUP BY QuestionText;';
        $rows = $dao->query($query, $request->get('pnameShort'), $request->get('courseCode'));
        $averages = [];
        foreach ($rows as $row) {
            $averages[] = [
                "SOCode" => $row["SOCode"],
                "Question" => $row["QuestionText"],
                "AVG" => $row["AVG(Weight_Value)"]
            ];
        }
        return json_encode($averages);
    }

    public function getRubricsSurveyCourse($request) {
        // -- retrieve rubrics based survey for a given course
        $dao = new ABETDAO();
        $query = 'SELECT SOCODE, QuestionText, Weight_Name, Weight_Value, Answer '
                . 'FROM ABET.QA QA, ABET.ANSWER A, ABET.Question Q, ABET.SurveyType ST, ABET.StudentOutcome SO, ABET.Program P, ABET.COURSE C '
                . 'WHERE QA.QUESTION_QID = Q.QID '
                . 'AND QA.ANSWER_AID = A.AID '
                . 'AND Q.SURVEYTYPEID = ST.SURVEYTYPEID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND C.COURSEID = Q.COURSE_COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND P.PNAMESHORT = ? '
                . 'AND C.COURSECODE = ? '
                . 'AND ST.SURVEYNAME = \'Rubrics-Based\' '
                . 'ORDER BY SO.SOCODE, QUESTIONTEXT, Weight_Value;';
        $rows = $dao->query($query, $request->get('pnameShort'), $request->get('courseCode'));
        $survey = [];
        foreach ($rows as $row) {
            $survey[] = [
                "SOCode" => $row["SOCode"],
                "Question" => $row["QuestionText"],
                "weightName" => $row["Weight_Name"],
                "weightValue" => $row["Weight_Value"],
                "Answer" => $row["Answer"]
            ];
        }
        return json_encode($survey);
    }

    public function getAllRubrics($request) {
        // — — retrieve all rubrics (can be modified for a given Program)
        $dao = new ABETDAO();
        $query = 'select qaid, QuestionText, weight_value, weight_name '
                . 'from abet.qa , abet.question , abet.SurveyType, abet.answer '
                . 'where SurveyName = \'Rubrics-Based\' '
                . 'and qa.Question_QID = qid '
                . 'and Question.SurveyTypeID = SurveyType.SurveyTypeID '
                . 'and qa.Answer_AID = aid;';
        $rows = $dao->query($query);
        $rubrics = [];
        foreach ($rows as $row) {
            $rubrics[] = [
                "QAID" => $row["SOCode"],
                "Question" => $row["QuestionText"],
                "weightName" => $row["Weight_Name"],
                "weightValue" => $row["Weight_Value"]
            ];
        }
        return json_encode($rubrics);
    }

    public function getAvgAllSOCourse1($request) {
        // -- retrieve the average for all the SO in a given term for a course
        $dao = new ABETDAO();
        $query = 'SELECT SOCode , AVG(Weight_Value) '
                . 'FROM ABET.StudentOutcome SO, ABET.Question Q, ABET.Course C, ABET.SurveyType ST, ABET.QA QA, ABET.Answer A, ABET.StudentQA SQA, ABET.Program P, ABET.Semester SEM, ABET.SECTION SEC, ABET.Student_Section SS '
                . 'WHERE SQA.QA_QAID = QA.QAID '
                . 'AND SEM.SEMESTERID = SEC.SEMESTERID '
                . 'AND SS.SECTIONID = SEC.SECTIONID '
                . 'AND SS.SSID = SQA.STUDENT_SECTION_SSID '
                . 'AND QA.Answer_AID = A.AID '
                . 'AND QA.Question_QID = Q.QID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND Q.SURVEYTYPEID = ST.SURVEYTYPEID '
                . 'AND ST.SURVEYNAME = \'Rubrics-Based\' '
                . 'AND Q.COURSE_COURSEID = C.COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND P.PNAMESHORT = ? '
                . 'AND C.COURSECODE = ? '
                . 'AND SEM.SEMESTERNUM = ? '
                . 'GROUP BY SOCODE;';
        $rows = $dao->query($query, $request->get('pnameShort'), $request->get('courseCode'), $request->get('semester'));
        $survey = [];
        foreach ($rows as $row) {
            $survey[] = [
                "SOCode" => $row["SOCode"],
                "AVG" => $row["AVG(Weight_Value)"]
            ];
        }
        return json_encode($survey);
    }

    public function getStudentRawDataCourseTerm($request) {
        // — — retrieve all rubrics (can be modified for a given Program)
        $dao = new ABETDAO();
        $query = 'select qaid, QuestionText, weight_value, weight_name '
                . 'from abet.qa , abet.question , abet.SurveyType, abet.answer '
                . 'where SurveyName = \'Rubrics-Based\' '
                . 'and qa.Question_QID = qid '
                . 'and Question.SurveyTypeID = SurveyType.SurveyTypeID '
                . 'and qa.Answer_AID = aid;';
        $rows = $dao->query($query);
        $rubrics = [];
        foreach ($rows as $row) {
            $rubrics[] = [
                "QAID" => $row["SOCode"],
                "Question" => $row["QuestionText"],
                "weightName" => $row["Weight_Name"],
                "weightValue" => $row["Weight_Value"]
            ];
        }
        return json_encode($rubrics);
    }

    public function getAvgAllSOCourse($request) {
        // -- retrieve raw data for students in a course in a specific term (rubrics based)
        $dao = new ABETDAO();
        $query = 'SELECT SUID, SemesterNum, SOCode, PNameShort, CourseCode, QuestionText, answer, Weight_Value, Weight_Name '
                . 'FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO, ABET.SurveyType ST, ABET.Course C, ABET.Program P '
                . 'WHERE SEM.SEMESTERNUM = ? '
                . 'AND SEM.SEMESTERID = SEC.SEMESTERID '
                . 'AND SEC.SECTIONID = SS.SECTIONID '
                . 'AND SS.STUDENT_STUDENTID = STU.STUDENTID '
                . 'AND SS.SSID = SQA.STUDENT_SECTION_SSID '
                . 'AND SQA.QA_QAID = QA.QAID '
                . 'AND QA.Answer_AID = A.AID '
                . 'AND QA.Question_QID = Q.QID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND Q.SURVEYTYPEID = ST.SURVEYTYPEID '
                . 'AND ST.SURVEYNAME = \'Rubrics-Based\' '
                . 'AND Q.COURSE_COURSEID = C.COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND P.PNAMESHORT = ? '
                . 'AND C.COURSECODE = ? '
                . 'GROUP BY SOCODE;';
        $rows = $dao->query($query, $request->get('semester'), $request->get('pnameShort'), $request->get('courseCode'));
        $rawData = [];
        foreach ($rows as $row) {
            $rawData[] = [
                "SUID" => $row["SUID"],
                "Semester" => $row["SemesterNum"],
                "SOCode" => $row["SOCode"],
                "pnameShort" => $row["PNameShort"],
                "courseCode" => $row["CourseCode"],
                "Question" => $row["QuestionText"],
                "Anwser" => $row["answer"],
                "weightName" => $row["Weight_Value"],
                "weightValue" => $row["Weight_Name"],
            ];
        }
        return json_encode($rawData);
    }

    function getCLOResponse($request) {
        $dao = new ABETDAO();
        $query = 'SET @sql = NULL; SELECT GROUP_CONCAT(DISTINCT CONCAT("MAX(IF(QuestionText = """, QuestionText,""", Weight_Name, NULL)) AS Question_", OrderNo)) INTO @sql 
            FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO, 
            ABET.SurveyType ST, ABET.Course C, ABET.Program P, ABET.Faculty F 
            WHERE SEM.SEMESTERNUM = ? AND SEC.FACULTYID = F.FACULTYID AND F.EMAIL = ? AND SEM.SEMESTERID = SEC.SEMESTERID AND SEC.SECTIONID = SS.SECTIONID 
            AND SS.STUDENT_STUDENTID = STU.STUDENTID AND SS.SSID = SQA.STUDENT_SECTION_SSID AND SQA.QA_QAID = QA.QAID AND QA.Answer_AID = A.AID AND QA.Question_QID = Q.QID 
            AND Q.STUDENTOUTCOME_SOID = SO.SOID AND Q.SURVEYTYPEID = ST.SURVEYTYPEID 
            AND ST.SURVEYNAME = ? AND Q.COURSE_COURSEID = C.COURSEID AND C.PROGRAMID = P.PROGRAMID AND P.PNAMESHORT = ? AND C.COURSECODE = ? ;
            SET @sql = CONCAT(\'SELECT SUID, \', @sql, \' FROM ABET.SEMESTER SEM, ABET.Section SEC, ABET.Student_Section SS, ABET.Student STU, 
            ABET.StudentQA SQA, ABET.QA QA, ABET.Answer A, ABET.QUESTION Q, ABET.StudentOutcome SO, ABET.SurveyType ST, ABET.Course C, ABET.Program P 
            WHERE SEM.SEMESTERNUM = ? AND SEM.SEMESTERID = SEC.SEMESTERID AND SEC.SECTIONID = SS.SECTIONID AND SS.STUDENT_STUDENTID = STU.STUDENTID 
            AND SS.SSID = SQA.STUDENT_SECTION_SSID AND SQA.QA_QAID = QA.QAID AND QA.Answer_AID = A.AID AND QA.Question_QID = Q.QID 
            AND Q.STUDENTOUTCOME_SOID = SO.SOID AND Q.SURVEYTYPEID = ST.SURVEYTYPEID AND ST.SURVEYNAME = ? 
            AND Q.COURSE_COURSEID = C.COURSEID AND C.PROGRAMID = P.PROGRAMID AND P.PNAMESHORT = ? AND C.COURSECODE = ? GROUP BY SUID\'); 
            prepare stmt from @sql; 
            execute stmt;
            deallocate prepare stmt;';
        $rows = $dao->query($query, $request->get("semester"), $request->get("femail"), 'CLO-Based', $request->get("pname"), $request->get("courseCode"), $request->get("semester"), 'CLO-Based', $request->get("pname"), $request->get("courseCode"));
        echo json_encode($rows);
    }

    function display($request) {
        // $request->get("semester"), $request->get("femail"), 'CLO-Based', $request->get("pname"), $request->get("courseCode"), $request->get("semester"), 'CLO-Based', $request->get("pname"), $request->get("courseCode")
        // $request->get('Answer'), $request->get('weightValue'), $request->get('weightName'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'CLO-Based', $request->get('statusName'), $request->get('SOCOde'), $request->get('pname')
        $request->set('pname', 'ICS');
        $request->set('courseCode', '102');
        $request->set('femail', 'saleh');
        $request->set('semester', '152');
        $request->set('statusName', 'Active');
        $request->set('weightName', 'Strongly Disagree');
        $request->set('weightValue', '0');
        $request->set('Answer', 'Strongly Disagree');
        //$request->set('description', 'Second SO');
        $request->set('dateActivated', '2010-01-02');
        $request->set('dateDeactivated', '2011-02-06');
        $request->set('SOCode', 'a');

        $this->deleteEmpAnswer($request);
    }

}
