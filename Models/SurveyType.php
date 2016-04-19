<?php

class SurveyType {

    private $SurveyTypeID;
    private $SurveyName;
    private $StatusID;
    private $Description;
    private $DateActivated;
    private $DateDeactivated;

    function __construct($surveyTypeID, $surveyName, $statusID, $description, $dateActivated, $dateDeactivated) {
        $this->SurveyTypeID = $surveyTypeID;
        $this->SurveyName = $surveyName;
        $this->StatusID = $statusID;
        $this->Description = $description;
        $this->DateActivated = $dateActivated;
        $this->DateDeactivated = $dateDeactivated;
    }

    function getSurveyTypeID() {
        return $this->SurveyTypeID;
    }

    function getSurveyName() {
        return $this->SurveyName;
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

    function setSurveyTypeID($id) {
        $this->SurveyTypeID = $id;
    }

    function setSurveyName($name) {
        $this->SurveyName = $name;
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
