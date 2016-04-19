<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Employer
 *
 * @author Saleh
 */
class Employer {

    private $EmployerID;
    private $EmployerName;
    private $email;
    private $password;

    function __construct($employerID, $employerName, $EmployerEmail, $EmployerPass) {
        $this->EmployerID = $employerID;
        $this->EmployerName = $employerName;
        $this->email = $EmployerEmail;
        $this->password = $EmployerPass;
     
    }

    function getEmployerID() {
        return $this->EmployerID;
    }

    function getEmployerName() {
        return $this->EmployerName;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function setEmployerID($id) {
        $this->EmployerID = $id;
    }

    function setEmployerName($name) {
        $this->EmployerName = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($pass) {
        $this->password = $pass;
    }

}
