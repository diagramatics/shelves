<?php

class AccountModel {
  private $email;
  private $fName;
  private $lName;
  private $dob;

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

  public function getDob() {
    return $this->dob;
  }

  public function setDob($dob) {
    $this->dob = $dob;
  }
}

?>
