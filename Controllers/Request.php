<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author Saleh
 */
class Request {

    private $request = array();

    public function __construct() {
        if (!empty($_POST)) {
            $this->request = $_POST;
        }
        if (!empty($_GET)) {
            $this->request = $_GET;
        }
    }

    public function get($name) {
        if (\array_key_exists($name, $this->request)) {
            return $this->request[$name];
        }
        return '';
    }

    public function set($name, $value) {
        $this->request[$name] = $value;
    }

    public function getCommand() {
        if (isset($this->request['cmd'])) {
            return $this->request['cmd'];
        } else {
            return 'display';
        }
    }

    public function getGroup() {
        if (isset($this->request['grp'])) {
            return $this->request['grp'];
        } else {
            return 'DisplayLogin';
        }
    }

}
