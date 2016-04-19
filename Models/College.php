<?php

class College {

    private $CID;
    private $CName;
    private $UID;
    private $CNameShort;

    function __construct($cid, $name, $uid, $short) {
        $this->CID = $cid;
        $this->UID = $uid;
        $this->CName = $name;
        $this->CNameShort = $short;
    }

    function getCID() {
        return $this->CID;
    }

    function getUID() {
        return $this->UID;
    }

    function getCName() {
        return $this->CName;
    }

    function getCNameShort() {
        return $this->CNameShort;
    }

    function setCID($id) {
        $this->CID = $id;
    }

    function setUID($id) {
        $this->UID = $id;
    }

    function setCName($name) {
        $this->CName = $name;
    }

    function setCNameShort($short) {
        $this->CNameShort = $short;
    }

}
