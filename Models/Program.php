<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Program
 *
 * @author Saleh
 */
class Program {

    private $ProgramID;
    private $ProgramName;
    private $PNameShort;
    private $DID;

    function __construct($programID, $programName, $did, $pNameShort) {
        $this->ProgramID = $programID;
        $this->ProgramName = $programName;
        $this->PNameShort = $pNameShort;
        $this->DID = $did;
    }

    function getProgramID() {
        return $this->ProgramID;
    }

    function getDID() {
        return $this->DID;
    }

    function getProgramName() {
        return $this->ProgramName;
    }

    function getPNameShort() {
        return $this->PNameShort;
    }

    function setDID($id) {
        $this->DID = $id;
    }

    function setProgramID($id) {
        $this->ProgramID = $id;
    }

    function setName($name) {
        $this->ProgramName = $name;
    }

    function setShortName($short) {
        $this->PNameShort = $short;
    }

}
