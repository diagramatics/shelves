<?php

class Model {
  protected $database;

  public function __construct($database) {
    $this->database = $database;
  }

  protected function model($model) {
    require_once '../app/models/'. $model .'.php';
    return new $model($this->database);
  }
}

?>
