<?php

class BagModel {
  private $products;
  private $totalCost;

  public function getProducts() {
    return $this->products;
  }

  public function setProducts($products) {
    $this->products = $products;
  }

  public function getTotalCost() {
    return $this->totalCost;
  }

  public function setTotalCost($totalCost) {
    $this->totalCost = $totalCost;
  }
}

?>
