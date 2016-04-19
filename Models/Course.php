<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Course
 *
 * @author Saleh
 */
class Course {

    private $CourseID;
    private $CourseName;
    private $CourseCode;
    private $CourseCredit;
    private $DateActivated;
    private $DateDeactivated;
    private $ProgramID;

    function __construct($courseID, $courseName, $courseCode, $courseCredit, $dateActivated, $dateDeactivated, $programID) {
        $this->CourseID = $courseID;
        $this->CourseName = $courseName;
        $this->CourseCode = $courseCode;
        $this->CourseCredit = $courseCredit;
        $this->DateActivated = $dateActivated;
        $this->DateDeactivated = $dateDeactivated;
        $this->ProgramID = $programID;
    }

    function getProgramID() {
        return $this->ProgramID;
    }

    function getCourseID() {
        return $this->CourseID;
    }

    function getCourseName() {
        return $this->CourseName;
    }

    function getCourseCode() {
        return $this->CourseCode;
    }

    function getCourseCredit() {
        return $this->CourseCredit;
    }

    function getDateActivated() {
        return $this->DateActivated;
    }

    function getDateDeactivated() {
        return $this->DateActivated;
    }

    function setCourseID($id) {
        $this->CourseID = $id;
    }

    function setProgramID($id) {
        $this->ProgramID = $id;
    }

    function setCourseName($name) {
        $this->CourseName = $name;
    }

    function setCourseCode($code) {
        $this->CourseCode = $code;
    }

    function setCourseCredit($credit) {
        $this->CourseCredit = $credit;
    }

    function setDateActivated($date) {
        $this->DateActivated = $date;
    }

    function setDateDeactivated($date) {
        $this->DateDeactivated = $date;
    }

}
