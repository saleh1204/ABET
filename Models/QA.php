<?php

class QA {

    private $QAID;
    private $QID;
    private $AID;
    private $StatusID;

    function __construct($qaid, $qid, $aid, $statusID) {
        $this->QAID = $qaid;
        $this->QID = $qid;
        $this->AID = $aid;
        $this->StatusID = $statusID;
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

    function getStatusID() {
        return $this->StatusID;
    }

    function setQAID($id) {
        $this->QAID = $id;
    }

    function setQID($id) {
        $this->QID = $id;
    }

    function setStatusID($id) {
        $this->StatusID = $id;
    }

    function setAID($id) {
        $this->AID = $id;
    }

}
