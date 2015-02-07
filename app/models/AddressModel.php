<?php

class AddressModel extends Model {
  private $id;
  private $userID;
  private $unit;
  private $streetNo;
  private $streetName;
  private $streetType;
  private $city;
  private $postcode;
  private $state;
  private $primary;

  public function parse($raw) {
    $raw->addressID;
    $this->setUserID($raw->userID);
    $this->setUnit($raw->unit);
    $this->setStreetNo($raw->streetNo);
    $this->setStreetName($raw->streetName);
    $this->setStreetType($raw->street);
    $this->setCity($raw->city);
    $this->setPostcode($raw->postcode);
    $this->setState($raw->state);
    $this->setPrimary($raw->primaryAddress);
  }
  public function extract() {
    return array(
      'id' => $this->getID(),
      'userID' => $this->getUserID(),
      'unit' => $this->getUnit(),
      'streetNo' => $this->getStreetNo(),
      'streetName' => $this->getStreetName(),
      'streetType' => $this->getStreetType(),
      'city' => $this->getCity(),
      'postcode' => $this->getPostcode(),
      'state' => $this->getState(),
      'primary' => $this->getPrimary()
    );
  }


  public function getID()
  {
    return $this->id;
  }
  public function setID($id)
  {
    $this->id = $id;
  }


  public function getUserID()
  {
    return $this->userID;
  }
  public function setUserID($userID)
  {
    $this->userID = $userID;
  }


  public function getUnit()
  {
    return $this->unit;
  }
  public function setUnit($unit)
  {
    $this->unit = $unit;
  }


  public function getStreetNo()
  {
    return $this->streetNo;
  }
  public function setStreetNo($streetNo)
  {
    $this->streetNo = $streetNo;
  }


  public function getStreetName()
  {
    return $this->streetName;
  }
  public function setStreetName($streetName)
  {
    $this->streetName = $streetName;
  }


  public function getStreetType()
  {
    return $this->streetType;
  }
  public function setStreetType($streetType)
  {
    $this->streetType = $streetType;
  }


  public function getCity()
  {
    return $this->city;
  }
  public function setCity($city)
  {
    $this->city = $city;
  }


  public function getPostcode()
  {
    return $this->postcode;
  }
  public function setPostcode($postcode)
  {
    $this->postcode = $postcode;
  }


  public function getState()
  {
    return $this->state;
  }
  public function setState($state)
  {
    $this->state = $state;
  }


  public function getPrimary()
  {
    return $this->primary;
  }
  public function setPrimary($primary)
  {
    $this->primary = $primary;
  }
}

?>
