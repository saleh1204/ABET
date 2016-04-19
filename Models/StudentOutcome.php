<?php

class StudentOutcome {

    private $SOID;
    private $SOCode;
    private $StatusID;
    private $Description;
    private $DateActivated;
    private $DateDeactivated;
    

    function __construct($soid, $soCode, $statusID, $description, $dateActivated, $dateDeactivated) {
        $this->SOID = $soid;
        $this->SOCode = $soCode;
        $this->StatusID = $statusID;
        $this->Description = $description;
        $this->DateActivated = $dateActivated;
        $this->DateDeactivated = $dateDeactivated;
        
    }

    function getSOID() {
        return $this->SOID;
    }

    function getSOCode() {
        return $this->SOCode;
    }

    function getStatusID() {
        return $this->StatusID;
    }

    function getDescription() {
        return $this->Description;
    }

    function getDateActivated() {
        return $this->DateActivated;
    }

    function getDateDeactivated() {
        return $this->DateActivated;
    }

    function setSOID($id) {
        $this->SOID = $id;
    }

    function setSOCode($code) {
        $this->SOCode = $code;
    }

    function setStatusID($id) {
        $this->StatusID = $id;
    }

    function setDescription($description) {
        $this->Description = $description;
    }

    function setDateActivated($date) {
        $this->DateActivated = $date;
    }

    function setDateDeactivated($date) {
        $this->DateDeactivated = $date;
    }

}
