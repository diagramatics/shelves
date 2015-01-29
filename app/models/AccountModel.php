<?php

class AccountModel {
  private $id;
  private $email;
  private $fName;
  private $lName;
  private $specialSub;

  /**
    * Parse the raw data gotten from an SQL query statement
    * $raw The raw data in an associative array
    */
  public function parse($raw) {
    $this->id = $raw->userID;
    $this->email = $raw->email;
    $this->fName = $raw->fName;
    $this->lName = $raw->lName;
    $this->specialSub = $raw->specialSub;
  }

  public function getID() {
    return $this->id;
  }

  public function setID($id) {
    $this->id = $id;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getFName() {
    return $this->fName;
  }

  public function setFName($fName) {
    $this->fName = $fName;
  }

  public function getLName() {
    return $this->lName;
  }

  public function setLName($lName) {
    $this->lName = $lName;
  }

  public function getSpecialSub() {
    return $this->specialSub;
  }

  public function setSpecialSub($specialSub) {
    $this->specialSub = $specialSub;
  }


  public function isLoggedIn() {
    return isset($_SESSION["email"]);
  }
}

?>
