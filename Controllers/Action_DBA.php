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
        $query = 'SELECT StatusID FROM ABET.Status WHERE StatusType = ?';
        $result = $dao->query($query, $request->get('statusType'));
        $query = 'INSERT INTO SurveyType (SurveyName, StatusID, Description, DateActivated, DateDeactivated) VALUES (?, ?, ?, ?, ?)';
        $result = $dao->query($query, $request->get('surveyName'), $result[0]['StatusID'], $request->get('description'), $request->get('dateActivated'), $request->get('dateDeactivated'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getSurveyType($request) {
        $dao = new ABETDAO();
        $query = 'SELECT ST.Description, ST.SurveyName, ST.DateActivated, ST.DateDeactivated,S.StatusType FROM abet.SurveyType ST, abet.Status S where ST.StatusID = S.StatusID;';
        $rows = $dao->query($query);
        $surveyTypes = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $surveyTypes[] = [
                    "SurveyType" => $row["SurveyName"],
                    "Status" => $row["StatusType"],
                    "description" => $row["Description"],
                    "dateActivated" => $row["DateActivated"],
                    "dateDeactivated" => $row["DateDeactivated"],
                ];
            }
        }
        $encoded = json_encode($surveyTypes);
        echo $encoded;
    }

    public function updateSurveyType($request) {
        $dao = new ABETDAO();
        $query = 'SELECT StatusID FROM ABET.Status WHERE StatusType = ?';
        $result = $dao->query($query, $request->get('status'));
        $query = 'UPDATE ABET.SurveyType SET SurveyName = (?), DateActivated = (?), DateDeactivated = (?), Description = ?, StatusID = ? where SurveyName = ?;';
        $result = $dao->query($query, $request->get('surveyName'), $request->get('dateActivated'), $request->get('dateDeactivated'), $request->get('description'), $result[0]['StatusID'], $request->get('oldSurveyName'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteSurveyType($request) {
        $dao = new ABETDAO();
        $query = 'DELETE FROM ABET.SurveyType WHERE SurveyName = ?;';
        $result = $dao->query($query, $request->get('surveyName'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function addSection($request) {
        $dao = new ABETDAO();
        $query = 'INSERT INTO ABET.Section (SectionNum, CourseID, SemesterID, FacultyID) VALUES ('
                . '?,'
                . '(SELECT CourseID FROM ABET.Course WHERE CourseCode = ?),'
                . '(SELECT SemesterID FROM ABET.Semester WHERE SemesterNum = ?),'
                . ' (SELECT FacultyID FROM ABET.Faculty WHERE FacultyName = ?)'
                . ');';
        // $result[0]['FacultyID']
        $result = $dao->query($query, $request->get('sectionNum'), $request->get('courseCode'), $request->get('semesterNum'), $request->get('facultyName'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function getSections($request) {
        $dao = new ABETDAO();
        $query = 'SELECT DISTINCT P.PNameShort, C.CourseCode, SEM.SemesterNum, F.FacultyName, SEC.SectionNum, SEC.SectionID FROM abet.Section SEC, abet.Faculty F, abet.Semester SEM, abet.Course C, abet.Program P '
                . 'WHERE SEC.SemesterID = SEM.SemesterID AND SEC.FacultyID = F.FacultyID AND C.CourseID = SEC.CourseID AND C.ProgramID = P.ProgramID';
        $rows = $dao->query($query);
        $sections = [];
        if ($rows != false) {
            foreach ($rows as $row) {
                $sections[] = [
                    "PName" => $row["PNameShort"],
                    "courseCode" => $row["CourseCode"],
                    "semester" => $row["SemesterNum"],
                    "FacultyName" => $row["FacultyName"],
                    "SectionNum" => $row["SectionNum"]
                ];
            }
        }
        $encoded = json_encode($sections);
        echo $encoded;
    }

    public function updateSection($request) {
        $dao = new ABETDAO();
        $query = 'UPDATE ABET.Section SET SectionNum = (?), CourseID = (SELECT COURSEID FROM COURSE WHERE COURSECODE = ?), SemesterID = (SELECT SemesterID from abet.Semester where SemesterNum = ?), FacultyID = (SELECT FacultyID FROM Faculty WHERE FacultyName = ?) '
                . 'WHERE SectionNum = ? AND SemesterID = (SELECT SemesterID from abet.Semester where SemesterNum = ?) AND FacultyID = (SELECT FacultyID FROM Faculty WHERE FacultyName = ?) AND CourseID = (SELECT COURSEID FROM COURSE WHERE COURSECODE = ? AND ProgramID = (SELECT ProgramID FROM Program WHERE PNameShort = ?));';
        $result = $dao->query($query, $request->get('newSectionNum'), $request->get('newCourseCode'), $request->get('newSemester'), $request->get('newFacultyName'), $request->get('sectionNum'), $request->get('semesterNum'), $request->get('facultyName'), $request->get('courseCode'), $request->get('pname'));
        $encoded = json_encode($result);
        echo $encoded;
    }

    public function deleteSection($request) {
        $dao = new ABETDAO();
        // pname, courseCode
        $query = 'DELETE FROM ABET.Section WHERE sectionNum = ? AND SemesterID = (SELECT SemesterID from abet.Semester where SemesterNum = ?) AND FacultyID = (SELECT FacultyID FROM Faculty WHERE FacultyName = ?) AND CourseID = (SELECT COURSEID FROM COURSE WHERE COURSECODE = ? AND ProgramID = (SELECT ProgramID FROM Program WHERE PNameShort = ?));';
        $result = $dao->query($query, $request->get('sectionNum'), $request->get('semesterNum'), $request->get('facultyName'), $request->get('courseCode'), $request->get('pname'));
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
      //  $request->set("sectionNum", "0");
      //  $request->set("courseCode", "233");
     //   $request->set("semesterNum", "151");
      //  $request->set("facultyName", "Salah");
      //  $request->set("newSectionNum", "2");
       // $request->set("newCourseCode", "233");
       // $request->set("newSemester", "152");
      //  $request->set("newFacultyName", "Salah");
       // $request->set("sectionNum", "2");
       // $request->set("pname", "CS");
        //$request->set("datedeActivated", "2011-02-03");
        return $this->getStudents($request);
    }

}
