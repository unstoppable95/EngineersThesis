<?php
 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbName = "school_schema";
class MyDB extends mysqli {
    public function __construct($host = "localhost", $username = "root", $password = "", $dbname = "school_schema", $port = NULL, $socket = NULL) {
      parent::__construct($host, $username, $password, $dbname, $port, $socket);
      $this->set_charset("utf8");
    }
  }
?>