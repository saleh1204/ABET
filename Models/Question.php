<?php

class Question {

    private $QID;
    private $RubricsNO;
    private $OrderNO;
    private $Question;
    private $DateActivated;
    private $DateDeactivated;
    private $SurveyTypeID;
    private $StatusID;
    private $PCID;
    private $SurveyID;
    private $SOID;
    private $CourseID;

    function __construct($QID, $RubricsNo, $orderNo, $question, $dateActivated, $dateDeactivated, $surveyTypeID, $statusID, $pcid, $surveyID, $soID, $courseID) {
        $this->AID = $QID;
        $this->RubricsNO = $RubricsNo;
        $this->OrderNO = $orderNo;
        $this->Question = $question;
        $this->DateActivated = $dateActivated;
        $this->DateDeactivated = $dateDeactivated;
        $this->SurveyTypeID = $surveyTypeID;
        $this->StatusID = $statusID;
        $this->PCID = $pcid;
        $this->SurveyID = $surveyID;
        $this->SOID = $soID;
        $this->CourseID = $courseID;
    }

    function getSurveyTypeID() {
        return $this->SurveyTypeID;
    }

    function getQID() {
        return $this->QID;
    }

    function getStatusID() {
        return $this->StatusID;
    }

    function getQuestion() {
        return $this->Question;
    }

    function getRubricsNo() {
        return $this->RubricsNO;
    }

    function getOrderNo() {
        return $this->OrderNO;
    }

    function getPCID() {
        return $this->PCID;
    }

    function getSurveyID() {
        return $this->SurveyID;
    }

    function getSOID() {
        return $this->SOID;
    }

    function getCourseID() {
        return $this->CourseID;
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

    function setQID($id) {
        $this->QID = $id;
    }

    function setStatusID($id) {
        $this->StatusID = $id;
    }

    function setQuestion($question) {
        $this->Answer = $question;
    }

    function setRubricsNo($rubrics) {
        $this->RubricsNO = $rubrics;
    }

    function setOrderNo($order) {
        $this->OrderNO = $order;
    }

    function setPCID($id) {
        $this->PCID = $id;
    }

    function setSurveyID($id) {
        $this->SurveyID = $id;
    }

    function setSOID($id) {
        $this->SOID = $id;
    }

    function setCourseID($id) {
        $this->CourseID = $id;
    }

    function setDateActivated($date) {
        $this->DateActivated = $date;
    }

    function setDateDeactivated($date) {
        $this->DateDeactivated = $date;
    }

}
