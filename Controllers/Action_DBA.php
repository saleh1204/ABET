<?php

require_once 'ABETDAO.php';

class Action_DBA {

    public function addUinversity($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.University (UName, UNameShort) values (\'' . $request->get('uname') . '\', \''
                . $request->get('unameshort') . '\');';
        $dao->excuteQuery($query);
    }

    public function addCollege($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.College (CName, UID, CNameShort) values (\'' . $request->get('cname') . '\',' .
                $request->get('uid') . ', \'' . $request->get('cnameshort') . '\');';
        $dao->excuteQuery($query);
    }

    public function addDepartment($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Department (DName, CID, DNameShort) values (\'' . $request->get('dname') .
                '\', (SELECT CID from ABET.College where CNameShort = \'' . $request->get('cnameshort') . '\'), \'' .
                $request->get('dnameshort') . '\');';
        $dao->excuteQuery($query);
    }

    public function addProgram($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Program (ProgramName, DID, PNameShort) values (\'' . $request->get('programName') .
                '\', (select DID from Department where DNameShort = \'' . $request->get('dnameshort') . '\'));,\'' .
                $request->get('pnameShort') . '\');';
        $dao->excuteQuery($query);
    }

    public function addCourse($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.course (CourseCode, CourseName, CourseCredit, ProgramID) values (\'' . $request->get('courseCode') . '\',\'' .
                $request->get('courseName') . '\',' . $request->get('courseCredit') . ',\' (SELECT ProgramID FROM ABET.Program WHERE PNameShort =\'' .
                $request->get('pnameShort') . '\'));';
        $dao->excuteQuery($query);
    }

    public function addFaculty($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Faculty (FacultyName, Email, Password, Department_DID) values (\'' . $request->get('facultyName') . '\', \'' .
                $request->get('facultyEmail') . '\', \'' . $request->get('password') . '\', (select DID from Department where DNameShort = \'' .
                $request->get('dnameshort') . '\'));';
        $dao->excuteQuery($query);
    }

    public function addSemester($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.semester (semesternum) values (\'' . $request->get('semseterNum') . '\');';
        $dao->excuteQuery($query);
    }

    public function addEmployer($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Employer (EmployerName, email, password) values (\'' . $request->get('employerName') .
                '\', \'' . $request->get('employerEmail') . '\', \'' . $request->get('employerPass') . '\');';
        $dao->excuteQuery($query);
    }

    public function addStatus($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Status (StatusType, StatusName) values (\'' . $request->get('statusType') . '\', \'' .
                $request->get('statusName') . '\');';
        $dao->excuteQuery($query);
    }

    public function addSurveyType($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.SurveyType (SurveyName, StatusID) values (\'' . $request->get('surveyName') . '\', \'' .
                '(select StatusID from ABET.Status where StatusType = \'Survey\' and StatusName = \'Valid\'));';
        $dao->excuteQuery($query);
    }

    public function addSection($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Section (SectionNum, CourseID, SemesterID, Faculty_FacultyID) values (' . $request->get('sectionNum') . ',\'' .
                '(select CourseID from ABET.Course where CourseCode = ' . $request->get('courseCode') . ')' . '\',' .
                '(select SemesterID from Abet.Semester where SemesterNum = ' . $request->get('sectionNum') . ')' .
                ',\' (select FacultyID from abet.Faculty where Email =\'' . $request->get('facultyEmail') . '\'));';
        $result = $dao->excuteQuery($query);
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addStudent($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Student (SUID, StudentName) values (? , ?);';
        $result = $dao->query($query, $request->get('studentID'), $request->get('studentName'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function updateStudent($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.STUDENT SET SUID = (?), StudentName = (?) WHERE SUID = (?);';
        $result = $dao->query($query, $request->get('newID'), $request->get('newName'), $request->get('oldID'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteStudent($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.STUDENT WHERE SUID = (?);';
        $result = $dao->query($query, $request->get('ID'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getStudents($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Student;';
        $rows = $dao->query($query);
        $students = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $students[] = [
                    "StudentID" => $row["StudentID"],
                    "SUID" => $row["SUID"],
                    "STUName" => $row["StudentName"],
                    "DateCreated" => $row["DateCreated"]
                ];
            }
        }
        $encoded = json_encode($students);
        echo $encoded;
    }

    public function getUniversities($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.UNIVERSITY;';
        $rows = $dao->query($query);
        $universities = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $universities[] = [
                    "UID" => $row["UID"],
                    "UName" => $row["UName"]
                ];
            }
        }
        $encoded = json_encode($universities);
        echo $encoded;
    }

    public function display($request) {
        return $this->getStudents($request);
    }

}
