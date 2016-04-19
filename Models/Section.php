<?php

class Section {

    private $CourseID;
    private $SectionID;
    private $SectionNum;
    private $SemesterID;
    private $Enrolled;
    private $FacultyID;
    private $EmployerID;

    function __construct($sectionID, $sectionNum, $courseID, $semesterID, $enrolled, $facultyID, $employerID) {
        $this->SectionID = $sectionID;
        $this->CourseID = $courseID;
        $this->SectionNum = $sectionNum;
        $this->SemesterID = $semesterID;
        $this->Enrolled = $enrolled;
        $this->FacultyID = $facultyID;
        $this->EmployerID = $employerID;
    }

    function getSectionID() {
        return $this->SectionID;
    }

    function getCourseID() {
        return $this->CourseID;
    }

    function getSectionNum() {
        return $this->SectionNum;
    }

    function getSemesterID() {
        return $this->SemesterID;
    }

    function getEnrolled() {
        return $this->Enrolled;
    }

    function getFacultyID() {
        return $this->FacultyID;
    }

    function getEmployerID() {
        return $this->EmployerID;
    }

    function setCourseID($id) {
        $this->CourseID = $id;
    }

    function setSectionID($id) {
        $this->SectionID = $id;
    }

    function setSectionNum($num) {
        $this->SectionNum = $num;
    }

    function setSemesterID($id) {
        $this->SemesterID = $id;
    }

    function setFacultyID($id) {
        $this->FacultyID = $id;
    }

    function setEmployerID($id) {
        $this->EmployerID = $id;
    }

    function setEnrolled($enrolled) {
        $this->Enrolled = $enrolled;
    }

}
