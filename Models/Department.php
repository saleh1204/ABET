<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Department
 *
 * @author Saleh
 */
class Department {
    private $DID;
    private $DName;
    private $CID;
    private $DNameShort;

    function __construct($did, $name, $cid, $short) {
        $this->DID = $did;
        $this->CID = $cid;
        $this->DName = $name;
        $this->DNameShort = $short;
    }

    function getCID() {
        return $this->CID;
    }

    function getDID() {
        return $this->DID;
    }

    function getDName() {
        return $this->DName;
    }

    function getDNameShort() {
        return $this->DNameShort;
    }

    function setCID($id) {
        $this->CID = $id;
    }

    function setDID($id) {
        $this->DID = $id;
    }

    function setDName($name) {
        $this->DName = $name;
    }

    function setDNameShort($short) {
        $this->DNameShort = $short;
    }
}
