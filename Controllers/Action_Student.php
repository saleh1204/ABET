<?php

require_once 'ABETDAO.php';

class Action_Student {

    function getCourses($request) {
        $dao = new ABETDAO();
        $query = 'SELECT `program`.`PNameShort`, `course`.`CourseCode`, `semester`.`SemesterNum`, `student`.`SUID`, `Section`.`SectionNum`'
                . 'FROM `program` JOIN `course` ON `course`.`ProgramID` = `program`.`ProgramID` '
                . 'JOIN `section` ON `section`.`CourseID` = `course`.`CourseID` '
                . 'JOIN `semester` ON `section`.`SemesterID` = `semester`.`SemesterID` '
                . 'JOIN `student_section` ON `student_section`.`SectionID` = `section`.`SectionID` '
                . 'JOIN `student` ON `student_section`.`Student_StudentID` = `student`.`StudentID` '
                . 'WHERE student.SUID = ? AND isCLOFilled = 0 order by semesternum, pnameshort, coursecode, sectionnum';
        $rows = $dao->query($query, $request->get('ID'));
        $courses = [];
        foreach ($rows as $row) {
            $courses[] = [
                "pnameShort" => $row["PNameShort"],
                "courseCode" => $row["CourseCode"],
                "semester" => $row["SemesterNum"],
                "section" => $row["SectionNum"]
            ];
        }
        echo json_encode($courses);
    }

    public function addStudentQA1($request) {
        $dao = new ABETDAO();
        $surveyName = 'CLO-Based';
        if ($request->get('courseCode') == '999') {
            $surveyName = 'Exit-Based';
        } else if ($request->get('courseCode') == '399' || $request->get('courseCode') == '350' || $request->get('courseCode') == '351' || $request->get('courseCode') == '352') {
            $surveyName = 'Employer-Based';
        }
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
                AND A.WEIGHT_NAME = ?
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
        //echo json_encode($tmpRows) . '<br />';

        for ($i = 0; $i < count($request->get("answers")); $i++) {
            $rows = $dao->query($query, $tmpRows[0]["SSID"], $surveyName, $request->get("courseCode"), $request->get("pname"), $request->get("answers")[$i], $request->get("questions")[$i]);
            ##print_r($rows);
            // echo '<br />';
        }

        $query1 = 'UPDATE student_section SET isCLOFilled = 1 '
                . 'WHERE Student_StudentID = (select StudentID from Student where SUID = ?) '
                . 'AND SectionID = (select SectionID from Section where sectionNum = ? '
                . 'AND CourseID = (select CourseID from Course where CourseCode = ? '
                . 'AND ProgramID = (select ProgramID from Program where PNameShort = ?)));';
        $rows = $dao->query($query1, $request->get("ID"), $request->get("section"), $request->get("courseCode"), $request->get("pname"));
        //echo json_encode(count($request->get("answers")));
        //$rows = $dao->query($query, $request->get("ID"), $request->get("courseCode"), $request->get("pname"), $request->get("section"), $request->get("courseCode"), $request->get("pname"), $request->get("answer"));
        echo json_encode($rows);
    }

    function display($request) {
        $request->set("ID", "201154810");
        $request->set("pname", "COE");
        $request->set("courseCode", "309");
        $request->set("section", "4");
        $answers[] = ['Agree'];
        $questions[] = ['Ability to do something'];
        $request->set("answers", $answers);
        $request->set("questions", $questions);
        $this->addStudentQA1($request);
    }

    public function getSurveyStudent($request) {
        $dao = new ABETDAO();
        $surveyName = 'CLO-Based';
        if ($request->get('courseCode') == '999') {
            $surveyName = 'Exit-Based';
        } else if ($request->get('courseCode') == '399' || $request->get('courseCode') == '350' || $request->get('courseCode') == '351' || $request->get('courseCode') == '352') {
            $surveyName = 'Employer-Based';
        }
        $query1 = "select distinct weight_name from abet.answer a, abet.surveytype st, "
                . "abet.status stay, abet.program p where stay.statusid = a.status_statusid "
                . "and p.programid = a.program_programid and a.surveytype_surveytypeid = st.surveytypeid "
                . "and st.surveyname = ? and pnameshort = ? and stay.statustype = ? and stay.statusname = ? order by weight_value desc;";
        $rows1 = $dao->query($query1, $surveyName, $request->get("pname"), "Survey", "Active");
// $data = [];
        $columns = "";
        //echo json_encode($rows1) . '<br />';

        $i = 0;
        for ($i = 0; $i < count($rows1); $i++) {
            $columns = $columns . "MAX(IF(weight_name = '" . $rows1[$i]["weight_name"] . "', Weight_Name, NULL)) AS Answer_" . ($i + 1) . ""; //$row["questiontext"];
            if ($i < count($rows1) - 1) {
                $columns = $columns . ", ";
            }
        }
        // echo '<br />' . $columns . ' End <br>';

        $query2 = 'SELECT questiontext, ' . ($columns) . ' FROM ABET.Question Q, ABET.Answer A, ABET.QA TQA,
          ABET.Course C, ABET.Program P, ABET.Status, ABET.SurveyType ST
          WHERE TQA.Answer_AID = A.AID AND TQA.Question_QID = Q.QID
          AND Q.COURSE_COURSEID = C.COURSEID AND Q.SURVEYTYPEID = ST.SURVEYTYPEID
          AND STATUS.STATUSID = A.STATUS_STATUSID AND C.COURSECODE = ? AND P.PNAMESHORT = ? AND StatusName = ?  AND StatusType = ?
          AND ST.SURVEYNAME = ? group by questiontext;';
        $rows = $dao->query($query2, $request->get('courseCode'), $request->get('pname'), 'Active', 'Survey', $surveyName);
        //echo json_encode($rows);
        $studentQA = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $answers = [];
                for ($j = 0; $j < count($row) - 1; $j++) {
                    $answers[] = $row["Answer_" . ($j + 1)];
                }
                $studentQA[] = [
                    "question" => $row["questiontext"],
                    "count" => count($rows1),
                    "answers" => $answers,
                ];
            }
        }
        print (json_encode($studentQA, JSON_PRETTY_PRINT));
    }

}
