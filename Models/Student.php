<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Student
 *
 * @author Saleh
 */
class Student {

    private $StudentID;
    private $StudentName;
    private $DateCreated;
    

    function __construct($studentID, $studentName, $dateCreated) {
        $this->StudentID = $studentID;
        $this->StudentName = $studentName;
        $this->DateCreated = $dateCreated;
    }

    function getStudentID() {
        return $this->StudentID;
    }

    function getStudentName() {
        return $this->StudentName;
    }

    function getDateCreated() {
        return $this->DateCreated;
    }

    function setStudentID($id) {
        $this->StudentID = $id;
    }

    function setStudentName($name) {
        $this->StudentName = $name;
    }

    function setDateCreated($date) {
        $this->DateCreated = $date;
    }


}
