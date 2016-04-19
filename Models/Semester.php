<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Semester
 *
 * @author Saleh
 */
class Semester {

    private $SemesterID;
    private $SemesterNum;
    private $StartDate;
    private $EndDate;

    function __construct($semesterID, $semesterNum, $startDate, $endDate) {
        $this->SemesterID = $semesterID;
        $this->SemesterNum = $semesterNum;
        $this->StartDate = $startDate;
        $this->EndDate = $endDate;
    }

    function getSemesterID() {
        return $this->SemesterID;
    }

    function getSemesterNum() {
        return $this->SemesterNum;
    }

    function getStartDate() {
        return $this->StartDate;
    }

    function getEndDate() {
        return $this->EndDate;
    }

    function setSemesterID($id) {
        $this->SemesterID = $id;
    }

    function setSemesterNum($num) {
        $this->SemesterNum = $num;
    }

    function setStartDate($date) {
        $this->StartDate = $date;
    }

    function setEndDate($date) {
        $this->EndDate = $date;
    }

}
