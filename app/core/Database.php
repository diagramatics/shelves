<?php

class Database extends mysqli {
  private $host = 'localhost';
  private $user = 'root';
  private $password = 'root';
  private $database = 'scotchbox';

  private $connection;

  public function __construct() {
    parent::__construct($this->host, $this->user, $this->password, $this->database);

    if (mysqli_connect_error()) {
      die('Connect Error ('. mysqli_connect_errno() .')'
      . mysqli_connect_error());
    }
  }

  public function close() {
    $this->close();
  }
}

?>
