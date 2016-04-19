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
class Action_DisplayLogin {
     public function display($request) {
        header("Location: ./Views/login.php");
    }
}
