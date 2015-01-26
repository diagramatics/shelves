<?php

class SubCategoryModel {
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

  public function getID() {
    return $this->subCatID;
  }

  public function getName() {
    return $this->subCatName;
  }
}

?>
