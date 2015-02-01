<?php

abstract class Model {
  protected $database;

  public function __construct($database) {
    $this->database = $database;
  }

  protected function model($model) {
    require_once '../app/models/'. $model .'.php';
    return new $model($this->database);
  }

  /**
    * Parse a raw value taken from database's getValue() or getValues() and organize the result to the model data
    * @param $raw The raw database value
    */
  public abstract function parse($raw);
  /**
    * Extract the values of the model into an array with the names as an associative array
    * Only use this to prepare an array to be passed to the view
    * @return An associative array containing the result
    */
  public abstract function extract();
}

?>
