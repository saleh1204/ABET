<?php

require_once 'ABETDAO.php';

class Action_Coordinator {

    public function addUinversity($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.University (UName, UNameShort) values (\'' . $request->get('uname') . '\', \''
                . $request->get('unameshort') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addCollege($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.College (CName, UID, CNameShort) values (\'' . $request->get('cname') . '\',' .
                $request->get('uid') . ', \'' . $request->get('cnameshort') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addDepartment($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Department (DName, CID, DNameShort) values (\'' . $request->get('dname') .
                '\', (SELECT CID from ABET.College where CNameShort = \'' . $request->get('cnameshort') . '\'), \'' .
                $request->get('dnameshort') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addProgram($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Program (ProgramName, DID, PNameShort) values (\'' . $request->get('programName') .
                '\', (select DID from Department where DNameShort = \'' . $request->get('dnameshort') . '\'));,\'' .
                $request->get('pnameshort') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addStatus($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Status (StatusType, StatusName) values (\'' . $request->get('statusType') . '\', \'' .
                $request->get('statusName') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addSurveyType($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.SurveyType (SurveyName, StatusID) values (\'' . $request->get('surveyName') . '\', \'' .
                '(select StatusID from ABET.Status where StatusType = \'Survey\' and StatusName = \'Valid\'));';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addStudentOutcome($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.StudentOutcome (SOCode, StatusID, Description, Program_ProgramID) values (\'' . $request->get('socode') . '\', \'' .
                '(select StatusID from ABET.Status where StatusType = \'StudentOutcome\' and StatusName = \'Activated\'), \'' . $request->get('description') .
                '\', (SELECT ProgramID FROM ABET.Program WHERE PNameShort =\'' . $request->get('pnameshort') . '\'));';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addCourse($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.course (CourseCode, CourseName, CourseCredit, ProgramID) values (\'' . $request->get('courseCode') . '\',\'' .
                $request->get('courseName') . '\',' . $request->get('courseCredit') . ',\' (SELECT ProgramID FROM ABET.Program WHERE PNameShort =\'' .
                $request->get('pnameshort') . '\'));';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addFaculty($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Faculty (FacultyName, Email, Password, Department_DID) values (\'' . $request->get('facultyName') . '\', \'' .
                $request->get('facultyEmail') . '\', \'' . $request->get('password') . '\', (select DID from Department where DNameShort = \'' .
                $request->get('dnameshort') . '\'));';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addSemester($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.semester (semesternum) values (\'' . $request->get('semseterNum') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addEmployer($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Employer (EmployerName, email, password) values (\'' . $request->get('employerName') .
                '\', \'' . $request->get('employerEmail') . '\', \'' . $request->get('employerPass') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addSection($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Section (SectionNum, CourseID, SemesterID, Faculty_FacultyID) values (' . $request->get('sectionNum') . ',\'' .
                '(select CourseID from ABET.Course where CourseCode = ' . $request->get('courseCode') . ')' . '\',' .
                '(select SemesterID from Abet.Semester where SemesterNum = ' . $request->get('sectionNum') . ')' .
                ',\' (select FacultyID from abet.Faculty where Email =\'' . $request->get('facultyEmail') . '\'));';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function addStudent($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Student (SUID, StudentName) values (' . $request->get('studentID') .
                ', \'' . $request->get('studentName') . '\');';
        $result = $dao->excuteQuery($query);
        // return json_encode($result);
    }

    public function updateStudent($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.STUDENT SET SUID = (?) WHERE SUID = (?);';
        $rows = $dao->query($query, $request->get('newID'), $request->get('oldID'));
    }

    public function deleteStudent($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.STUDENT WHERE SUID = (?);';
        $rows = $dao->query($query, $request->get('ID'));
    }

}
