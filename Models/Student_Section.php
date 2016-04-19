<?php

class Student_Section {

    private $StudentID;
    private $SectionID;
    private $DateCreated;

    function __construct($sectionID, $studentID, $dateCreated) {
        $this->StudentID = $studentID;
        $this->SectionID = $sectionID;
        $this->DateCreated = $dateCreated;
    }

    function getStudentID() {
        return $this->StudentID;
    }

    function getSectionID() {
        return $this->SectionID;
    }

    function getDateCreated() {
        return $this->DateCreated;
    }

    function setStudentID($id) {
        $this->StudentID = $id;
    }

    function setSectionID($id) {
        $this->SectionID = $id;
    }

    function setDateCreated($date) {
        $this->DateCreated = $date;
    }

}
