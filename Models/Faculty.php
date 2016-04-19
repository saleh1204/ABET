<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Faculty
 *
 * @author Saleh
 */
class Faculty {

    //put your code here
    private $FacultyID;
    private $FacultyName;
    private $Email;
    private $Password;
    private $DID;

    function __construct($facultyID, $facultyName, $facultyEmail, $facultyPass, $did) {
        $this->FacultyID = $facultyID;
        $this->FacultyName = $facultyName;
        $this->Email = $facultyEmail;
        $this->Password = $facultyPass;
        $this->DID = $did;
    }

    function getFacultyID() {
        return $this->FacultyID;
    }

    function getDID() {
        return $this->DID;
    }

    function getFacultyName() {
        return $this->FacultyName;
    }

    function getEmail() {
        return $this->Email;
    }

    function getPassword() {
        return $this->Password;
    }
    
    
    function setDID($id) {
        $this->DID = $id;
    }

    function setFacultyID($id) {
        $this->FacultyID = $id;
    }

    function setName($name) {
        $this->FacultyName = $name;
    }

    function setEmail($email) {
        $this->Email = $email;
    }
    function setPassword($pass) {
        $this->Password = $pass;
    }
}
