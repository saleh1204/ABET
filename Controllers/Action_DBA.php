<?php

require_once 'ABETDAO.php';

class Action_DBA {

    public function addUinversity($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.University (UName, UNameShort) values (\'' . $request->get('uname') . '\', \''
                . $request->get('unameshort') . '\');';
        $dao->excuteQuery($query);
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

    public function addCollege($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.College (CName, UID, CNameShort) values (? , 1, ?);';
        $result = $dao->query($query, $request->get('CName'), $request->get('CNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getColleges($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.College;';
        $rows = $dao->query($query);
        $colleges = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $colleges[] = [
                    "CID" => $row["CID"],
                    "CName" => $row["CName"],
                    "cnameShort" => $row["CNameShort"],
                    "UID" => $row["UID"]
                ];
            }
        }
        $encoded = json_encode($colleges);
        echo $encoded;
    }

    public function updateCollege($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.College SET CNameShort = (?), CName = (?) WHERE CNameShort = (?);';
        $result = $dao->query($query, $request->get('newCNameShort'), $request->get('CName'), $request->get('oldCNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteCollege($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.College WHERE cnameShort = ?;';
        $result = $dao->query($query, $request->get('CNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addDepartment($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Department (DName, CID, DNameShort) values (?,(SELECT CID from ABET.College where CNameShort = ? ), ?);';
        $result = $dao->query($query, $request->get('DName'), $request->get('CNameShort'), $request->get('DNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getDepartments($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Department D, abet.College C where D.CID = C.CID;';
        $rows = $dao->query($query);
        $colleges = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $colleges[] = [
                    "CID" => $row["CID"],
                    "DID" => $row["DID"],
                    "Dname" => $row["DName"],
                    "DnameShort" => $row["DNameShort"],
                    "CnameShort" => $row["CNameShort"],
                    "CName" => $row["CName"]
                ];
            }
        }
        $encoded = json_encode($colleges);
        echo $encoded;
    }

    public function updateDepartment($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Department SET DNameShort = (?), DName = (?), CID = (Select CID from ABET.College where CNameShort = ?) WHERE DNameShort = (?);';
        $result = $dao->query($query, $request->get('newDNameShort'), $request->get('DName'), $request->get('CNameShort'), $request->get('oldDNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteDepartment($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Department WHERE DNameShort = ?;';
        $result = $dao->query($query, $request->get('DNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addProgram($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Program (ProgramName, DID, PNameShort) values (?, (select DID from Department where DNameShort = ?), ?);';
        $result = $dao->query($query, $request->get('PName'), $request->get('DNameShort'), $request->get('PNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getPrograms($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Department D, abet.Program P where D.DID = P.DID;';
        $rows = $dao->query($query);
        $programs = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $programs[] = [
                    "DID" => $row["DID"],
                    "PName" => $row["ProgramName"],
                    "PNameShort" => $row["PNameShort"],
                    "DNameShort" => $row["DNameShort"],
                    "PID" => $row["ProgramID"]
                ];
            }
        }
        $encoded = json_encode($programs);
        echo $encoded;
    }

    public function updateProgram($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Program SET PNameShort = (?),  ProgramName = (?), DID = (Select DID from ABET.Department where DNameShort = ?) WHERE PNameShort = (?);';
        $result = $dao->query($query, $request->get('newPNameShort'), $request->get('PName'), $request->get('DNameShort'), $request->get('oldPNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteProgram($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Program WHERE PNameShort = ?;';
        $result = $dao->query($query, $request->get('PNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addCourse($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.course (CourseCode, CourseName, CourseCredit, ProgramID, DateActivated, DateDeactivated) values (? , ? , ? , (SELECT ProgramID FROM ABET.Program WHERE PNameShort = ? ), ?, ?);';
        $result = $dao->query($query, $request->get('courseCode'), $request->get('courseName'), $request->get('courseCredit'), $request->get('pnameShort'), $request->get('dateActivated'), $request->get('dateDeactivated'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getCourses($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Course C, abet.Program P where C.ProgramID = P.ProgramID;';
        $rows = $dao->query($query);
        $courses = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $courses[] = [
                    "CourseID" => $row["CourseID"],
                    "courseCode" => $row["CourseCode"],
                    "courseName" => $row["CourseName"],
                    "courseCredit" => $row["CourseCredit"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"],
                    "PName" => $row["PNameShort"]
                ];
            }
        }
        $encoded = json_encode($courses);
        echo $encoded;
    }

    public function updateCourse($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Course SET CourseCode = (?),  CourseName = (?), CourseCredit = (?), DateActivated = (?),DateDeactivated = (?), ProgramID = (Select Program from ABET.Program where PNameShort = ?) WHERE ProgramID = (Select Program from ABET.Program where PNameShort = ?) AND courseCode = (?);';
        $result = $dao->query($query, $request->get('newCourseCode'), $request->get('courseName'), $request->get('courseCredit'), $request->get('dateActivated'), $request->get('dateDeactivated'), $request->get('newPNameShort'), $request->get('oldPNameShort'), $request->get('oldcourseCode'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteCourse($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Course WHERE CourseCode = ? AND ProgramID = (Select ProgramID from ABET.Program where PNameShort = ?);';
        $result = $dao->query($query, $request->get('courseCode'), $request->get('PNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addFaculty($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.Faculty (FacultyName, Email, Password, Department_DID) values (?,?,"password",(select DID from Department where DNameShort = ?));';
        $result = $dao->query($query, $request->get('facultyName'), $request->get('facultyEmail'), $request->get('DNameShort'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getFaculties($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Faculty F, abet.Department D where D.DID = F.Department_DID;';
        $rows = $dao->query($query);
        $faculty = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $faculty[] = [
                    "DNameShort" => $row["DNameShort"],
                    "FacultyID" => $row["FacultyID"],
                    "FacultyName" => $row["FacultyName"],
                    "Email" => $row["Email"],
                ];
            }
        }
        $encoded = json_encode($faculty);
        echo $encoded;
    }

    public function updateFaculty($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Faculty SET FacultyName = (?), Email = (?), Department_DID = (Select DID from ABET.Department where DNameShort = ?) WHERE FacultyName = (?) AND Email = (?);';
        $result = $dao->query($query, $request->get('newName'), $request->get('Email'), $request->get('DNameShort'), $request->get('oldName'), $request->get('oldEmail'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteFaculty($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Faculty WHERE FacultyName = ?;';
        $result = $dao->query($query, $request->get('Name'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addSemester($request) {
        $dao = new ABETDAO();
        $query = 'insert into abet.semester (SemesterNum, StartDate, EndDate) values (?, ?, ?);';
        $request = $dao->query($query, $request->get('semesterNum'), $request->get('startDate'), $request->get('endDate'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getSemesters($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Semester;';
        $rows = $dao->query($query);
        $faculty = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $faculty[] = [
                    "semesterNum" => $row["SemesterNum"],
                    "startDate" => $row["StartDate"],
                    "endDate" => $row["EndDate"],
                ];
            }
        }
        $encoded = json_encode($faculty);
        echo $encoded;
    }

    public function updateSemester($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Semester SET SemesterNum = (?), StartDate = (?), EndDate = ? where SemesterNum = ?;';
        $result = $dao->query($query, $request->get('newSemester'), $request->get('startDate'), $request->get('endDate'), $request->get('oldSemester'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteSemester($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Semester WHERE SemesterNum = ?;';
        $result = $dao->query($query, $request->get('semesterNum'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addEmployer($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Employer (EmployerName, email, password) values (?,?,?);';
        $request = $dao->query($query, $request->get('empName'), $request->get('email'), $request->get('password'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getEmployers($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Employer;';
        $rows = $dao->query($query);
        $faculty = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $faculty[] = [
                    "empName" => $row["EmployerName"],
                    "email" => $row["email"],
                    "password" => $row["password"],
                ];
            }
        }
        $encoded = json_encode($faculty);
        echo $encoded;
    }

    public function updateEmployer($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Employer SET EmployerName = (?), email = (?), password = ? where EmployerName = ?;';
        $result = $dao->query($query, $request->get('newName'), $request->get('email'), $request->get('password'), $request->get('oldName'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteEmployer($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Employer WHERE EmployerName = ?;';
        $result = $dao->query($query, $request->get('name'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addStatus($request) {
        $dao = new ABETDAO();
        $query = 'insert into ABET.Status (StatusType, StatusName, Description) values (?, ?, ?);';
        $result = $dao->query($query, $request->get('statusType'), $request->get('statusName'), $request->get('description'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getStatus($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM abet.Status;';
        $rows = $dao->query($query);
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

    public function updateStatus($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Status SET StatusType = (?), StatusName = (?), Description = ? where StatusName = ?;';
        $result = $dao->query($query, $request->get('statusType'), $request->get('statusName'), $request->get('description'), $request->get('oldStatusName'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteStatus($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.Status WHERE StatusName = ?;';
        $result = $dao->query($query, $request->get('statusName'));
        $encoded = json_encode($result);
        echo $encoded;
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

    public function display($request) {
        return $this->getEmployers($request);
    }

}
