<?php

class SubCategoryModel extends Model{
  private $subCatID;
  private $subCatName;
  private $catID;

  /**
    * Sets model from the raw data gotten from the database
    *
    * $dbv The raw database values
    */
  public function setRaw($dbv) {
    $this->subCatID = $dbv->subCatID;
    $this->subCatName = $dbv->subCatName;
    $this->catID = $dbv->catID;
  }


  public function parse($raw) {
    $this->setID($raw->subCatID);
    $this->setName($raw->subCatName);
    $this->setCatID($raw->catID);
  }

  public function extract() {
    return array(
      "id" => $this->getID(),
      "name" => $this->getName(),
      "catID" => $this->getCatID(),
      "url" => '/products/subcategory/' . $this->getID() . '/' . Helpers::makeSlug($this->getName())
    );
  }

  public function getID() {
    return $this->subCatID;
  }

  public function setID($id) {
    $this->subCatID = $id;
  }

  public function getName() {
    return $this->subCatName;
  }

  public function setName($name) {
    $this->subCatName = $name;
  }

  public function getCatID() {
    return $this->catID;
  }

  public function setCatID($catID) {
    $this->catID = $catID;
  }
}

?>
