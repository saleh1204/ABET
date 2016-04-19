<?php

require_once 'ABETDAO.php';

class Action_Student {
/*
    public function getSurvey($request) {
        $dao = new ABETDAO();

        $query = '';
        $result = $dao->excuteQuery($query);
        return json_encode($result);
    }

    public function getAllSurveys($request) {
        $dao = new ABETDAO();

        $query = '';
        $result = $dao->excuteQuery($query);
        return json_encode($result);
    }
 */
 
/*
    public function addStudentQA($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.StudentQA (QA_QAID, Student_Section_SSID) values (' . $request->get('qaid') . ',' .
                ', ' . $request->get('ssid') . ');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }
*/
    public function addStudentQA($request) {
        $dao = new ABETDAO();
        $query = 'INSERT INTO ABET.STUDENTQA (STUDENT_SECTION_SSID, QA_QAID) VALUES '
                . '((SELECT SSID FROM ABET.PROGRAM P, ABET.COURSE C, ABET.SECTION SEC, '
                . 'ABET.FACULTY F, ABET.SEMESTER SEM, ABET.STUDENT STU, '
                . 'ABET.STUDENT_SECTION SS '
                . 'WHERE P.PROGRAMID = C.PROGRAMID '
                . 'AND C.COURSEID = SEC.COURSEID '
                . 'AND SEC.FACULTY_FACULTYID = F.FACULTYID '
                . 'AND SEC.SEMESTERID = SEM.SEMESTERID '
                . 'AND SEC.SECTIONID = SS.SECTIONID '
                . 'AND SS.STUDENT_STUDENTID = STU.STUDENTID '
                . 'AND STU.SUID = ? '
                . 'AND SEM.SEMESTERNUM = ? '
                . 'AND F.EMAIL = ? '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? )'
                . '(SELECT QAID '
                . 'FROM ABET.QA QA, ABET.QUESTION Q, ABET.ANSWER A, ABET.SURVEYTYPE SUT, ABET.STUDENTOUTCOME SO, ABET.COURSE C, ABET.PROGRAM P '
                . 'WHERE QA.QUESTION_QID = Q.QID '
                . 'AND QA.ANSWER_AID = A.AID '
                . 'AND Q.STUDENTOUTCOME_SOID = SO.SOID '
                . 'AND Q.COURSE_COURSEID = C.COURSEID '
                . 'AND C.PROGRAMID = P.PROGRAMID '
                . 'AND Q.SURVEYTYPEID = SUT.SURVEYTYPEID '
                . 'AND SUT.SURVEYNAME = \'RUBRICS-Based\' '
                . 'AND Q.RUBRICSNO = ? '
                . 'AND C.COURSECODE = ? '
                . 'AND P.PNAMESHORT = ? '
                . 'AND A.WEIGHT_NAME = ? '
                . 'AND SOCODE = ? ));';
        
        $rows = $dao->query($query, $request->get('SUID'), $request->get('Semester'), $request->get('facultyEmail'), 
                                    $request->get('courseCode'), $request->get('PNameShort'), $request->get('Rubric'), 
                                    $request->get('courseCode'), $request->get('PNameShort'), $request->get('PNameShort'), 
                                    $request->get('SOCODE'));
    }

}
