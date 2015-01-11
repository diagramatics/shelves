<?php

class ProductsModel {
  private $categories;


  public function setCategories($categories) {
    $this->categories = $categories;
  }

  public function getCategories() {
    return $this->categories;
  }
}

?>
