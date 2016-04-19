<?php

class Status {

    private $StatusID;
    private $StatusName;
    private $Description;

    function __construct($statusID, $statusName, $description) {
        $this->StudentID = $statusID;
        $this->StatusName = $statusName;
        $this->Description = $description;
    }

    function getStatusID() {
        return $this->StatusID;
    }

    function getStatusName() {
        return $this->StatusName;
    }

    function getDescription() {
        return $this->Description;
    }

    function setStatusID($id) {
        $this->StatusID = $id;
    }

    function setStatusName($name) {
        $this->StatusName = $name;
    }

    function setDescription($description) {
        $this->Description = $description;
    }

}
