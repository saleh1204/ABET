<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Action_DisplayLogin
 *
 * @author Saleh
 */
require_once 'ABETDAO.php';

class Action_DisplayLogin {

    public function display($request) {
        header("Location: index.html");
        //$request->set("femail", "saleh");
        //$request->set("fpassword", "password");
        //return $this->FacultyLogin($request);
    }

    public function FacultyLogin($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM Faculty WHERE email = ? AND password = ?;';
        $rows = $dao->query($query, $request->get('femail'), $request->get('fpassword'));
        $answer = [];
        if ($rows != false) {
            $answer[] = [
                "correct" => true
            ];
        } else {
            $answer[] = [
                "correct" => false
            ];
        }
        $encoded = json_encode($answer);
        echo $encoded;
    }

}
