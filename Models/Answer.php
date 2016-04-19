<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Answer
 *
 * @author Saleh
 */
class Answer {

    private $AID;
    private $Answer;
    private $RubricsNO;
    private $Weight_Value;
    private $Weight_Name;
    private $SurveyTypeID;
    private $StatusID;
    private $Description;
    private $DateActivated;
    private $DateDeactivated;

    function __construct($AID, $RubricsNo, $answer, $weightValue, $weightName, $dateActivated, $dateDeactivated, $surveyTypeID, $statusID) {
        $this->AID = $AID;
        $this->RubricsNO = $RubricsNo;
        $this->Answer = $answer;
        $this->Weight_Value = $weightValue;
        $this->Weight_Name = $weightName;
        $this->SurveyTypeID = $surveyTypeID;
        $this->StatusID = $statusID;
        $this->DateActivated = $dateActivated;
        $this->DateDeactivated = $dateDeactivated;
    }

    function getSurveyTypeID() {
        return $this->SurveyTypeID;
    }

    function getAID() {
        return $this->AID;
    }

    function getStatusID() {
        return $this->StatusID;
    }

    function getAnswer() {
        return $this->Answer;
    }

    function getRubricsNo() {
        return $this->RubricsNO;
    }

    function getWeightName() {
        return $this->Weight_Name;
    }

    function getWeightValue() {
        return $this->Weight_Value;
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

    function setAID($id) {
        $this->AID = $id;
    }

    function setStatusID($id) {
        $this->StatusID = $id;
    }

    function setAnswer($answer) {
        $this->Answer = $answer;
    }

    function setRubricsNo($rubrics) {
        $this->RubricsNO = $rubrics;
    }

    function setWeightName($name) {
        $this->Weight_Name = $name;
    }

    function setWeightValue($value) {
        $this->Weight_Value = $value;
    }

    function setDateActivated($date) {
        $this->DateActivated = $date;
    }

    function setDateDeactivated($date) {
        $this->DateDeactivated = $date;
    }

}
