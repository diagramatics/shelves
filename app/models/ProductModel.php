<?php

  class ProductModel {
    private $id;
    private $name;
    private $image;
    private $price;
    private $desc;
    private $priceUnit;

    public function getID() {
      return $this->id;
    }

    public function setID($id) {
      $this->id = $id;
    }

    public function getName() {
      return $this->name;
    }

    public function setName($name) {
      $this->name = $name;
    }

    public function getImage() {
      return $this->image;
    }

    public function setImage($image) {
      $this->image = $image;
    }

    public function getPrice() {
      return $this->price;
    }

    public function setPrice($price) {
      $this->price = $price;
    }

    public function getDesc() {
      return $this->desc;
    }

    public function setDesc($desc) {
      $this->desc = $desc;
    }

    public function getPriceUnit() {
      return $this->priceUnit;
    }

    public function setPriceUnit($priceUnit) {
      $this->priceUnit = $priceUnit;
    }
  }

?>
