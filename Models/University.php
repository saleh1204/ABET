<?php

class University {

    private $UID;
    private $UName;
    private $UnameShort;

    function __construct($id, $name, $shortName) {
        $this->UID = $id;
        $this->UName = $name;
        $this->UnameShort = $shortName;
    }

    function getUID() {
        return $this->UID;
    }

    function getUName() {
        return $this->UName;
    }

    function getUnameShort() {
        return $this->UnameShort;
    }

    function setUID($id) {
        $this->UID = $id;
    }

    function setUName($name) {
        $this->UName = $name;
    }

    function setUnameShort($short) {
        $this->UnameShort = $short;
    }

}
