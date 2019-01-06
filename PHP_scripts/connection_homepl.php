<?php

 $servername = "localhost";
 $username = "29579606_school_schema";
 $password = "Skarbnik.321";
 $dbName = "school_schema";

class MyDB extends mysqli {

    public function __construct($host = "localhost", $username = "29579606_school_schema", $password = "Skarbnik.321", $dbname = "29579606_school_schema", $port = 3306, $socket = NULL) {
      parent::__construct($host, $username, $password, $dbname, $port, $socket);
      $this->set_charset("utf8");
    }

  }
?>