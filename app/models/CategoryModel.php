<?php

class CategoryModel {
  private $catID;
  private $catName;
  private $items;

  public function getCatID() {
    return $this->catID;
  }

  public function setCatID($catID) {
    $this->catID = $catID;
  }

  public function getCatName() {
    return $this->catName;
  }

  public function setCatName($catName) {
    $this->catName = $catName;
  }

  public function getItems() {
    return $this->items;
  }

  public function setItems($items) {
    $this->items = $items;
  }
}

?>
