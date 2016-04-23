<?php

require_once 'ABETDAO.php';

class Action_Coordinator {

    public function addStudentOutcome($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.StudentOutcome (SOCode, StatusID, Description, Program_ProgramID) values (\'' . $request->get('SOCode') . '\', \'' .
                '(select StatusID from ABET.Status where StatusType = \'StudentOutcome\' and StatusName = \'Activated\'), \'' . $request->get('description') .
                '\', (SELECT ProgramID FROM ABET.Program WHERE PNameShort =\'' . $request->get('pnameShort') . '\'));';
        $dao->excuteQuery($query);
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

    public function getAvgAllSOCourse($request) {
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
        $rows = $dao->query($query, $request->get('semester'), $request->get('pnameShort'), $request->get('courseCode') );
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

}
