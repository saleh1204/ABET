<?php

class ABETDAO {

    private $_mysqli;
    private $_host = "localhost";
    private $_user = "root";
    private $_pass = "saleh";
    private $_db = "ABET";

    function __construct() {
        
    }

    function __destruct() {
        
    }

    private function getDBConnection() {
        if (!isset($this->_mysqli)) {
            $this->_mysqli = mysql_connect($this->_host, $this->_user, $this->_pass);
            if (!$this->_mysqli) {
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db($this->_db, $$this->mysqli);
        }
        return $this->_mysqli;
    }

    public function excuteQuery($query) {
        $this->getDBConnection();
        $result = mysql_query($query);
        if (mysql_errno()) {
            echo "MySQL error " . mysql_errno() . ": "
            . mysql_error() . "\n<br>When executing <br>\n$query\n<br>";
        }
        if (!$result) {
            die('Invalid query: ' . mysql_error());
            //$result = NULL;
        }
        return $result;
    }

    public function fetch($result) {
        $row = mysql_fetch_array($result);
        // mysql_fetch_assoc($res);
        return $row;
    }

    public function con_close() {
        mysql_close($$this->mysqli);
    }

    function query(/* $sql [, ... ] */) {
        // SQL statement
        $sql = func_get_arg(0);
        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);
        // try to connect to database
        static $handle;
        if (!isset($handle)) {
            try {
                // connect to database
                $handle = new PDO("mysql:dbname=" . $this->_db . ";host=" . $this->_host, $this->_user, $this->_pass);
                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (Exception $e) {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }
        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false) {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }
        // execute SQL statement
        $results = $statement->execute($parameters);
        // return result set's rows, if any
        if ($results !== false) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

}

?>
