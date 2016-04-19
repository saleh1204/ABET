<?php

class StudentQA {

    private $StudentQAID;
    private $StudentID;
    private $SectionID;
    private $QAID;
    private $QID;
    private $AID;

    function __construct($studentQAID, $studentID, $sectionID, $qaid, $qid, $aid) {
        $this->QAID = $qaid;
        $this->QID = $qid;
        $this->AID = $aid;
        $this->StudentQAID = $studentQAID;
        $this->SectionID = $sectionID;
        $this->StudentID = $studentID;
    }

    function getQAID() {
        return $this->QAID;
    }

    function getQID() {
        return $this->QID;
    }

    function getAID() {
        return $this->AID;
    }

    function getStudentID() {
        return $this->StudentID;
    }

    function getStudentQAID() {
        return $this->StudentQAID;
    }

    function getSectionID() {
        return $this->SectionID;
    }

    function setQAID($id) {
        $this->QAID = $id;
    }

    function setQID($id) {
        $this->QID = $id;
    }

    function setStudntID($id) {
        $this->StudentID = $id;
    }

    function setSectionID($id) {
        $this->SectionID = $id;
    }

    function setStudntQAID($id) {
        $this->StudentQAID = $id;
    }

    function setAID($id) {
        $this->AID = $id;
    }

}
