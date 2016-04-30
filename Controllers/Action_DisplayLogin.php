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

    public function StudentLogin($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM Student WHERE SUID = ? AND Password = ?;';
        $rows = $dao->query($query, $request->get('sID'), $request->get('spassword'));
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

    public function CoordinatorLogin($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM faculty join Program on Program.ProgramID = Faculty.Program_ProgramID WHERE Email = ? AND Password = ? AND Program_ProgramID is not null';
        $rows = $dao->query($query, $request->get("femail"), $request->get('fpassword'));
        $answer = [];
        if ($rows != false) {
            $answer[] = [
                "PName" => $rows[0]['PNameShort'],
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

    public function DBALogin($request) {
        $dao = new ABETDAO();
        $query = 'SELECT * FROM DBA WHERE email = ? AND password = ?;';
        $rows = $dao->query($query, $request->get('demail'), $request->get('dpassword'));
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
