<?php

class AccountModel {
  private $id;
  private $email;
  private $fName;
  private $lName;

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
}

?>
