<?php

  class ProductModel extends Model {
    private $id;
    private $name;
    private $image;
    private $basePrice;
    private $price;
    private $discount;
    private $desc;
    private $priceUnit;
    private $qty;

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
      $this->image = '/img/products/' . $image;
    }

    public function getBasePrice() {
      return $this->basePrice;
    }

    public function setBasePrice($price) {
      $this->basePrice = $price;
    }

    public function getPrice() {
      return $this->price;
    }

    public function setPrice($price) {
      $this->price = $price;


      // Get the biggest discount from a running promotion
      $discounts = $this->database->getValues("ProductPromotion as a, Promotion as b", ["a.discount", "b.startDate", "b.endDate"], [], [
        'WHERE a.prodID = "'.$this->id.'" AND a.promotionID = b.promotionID ORDER BY a.discount DESC'
      ]);

      $today = new DateTime(null);
      foreach ($discounts as $discount) {
        $startDate = new DateTime($discount->startDate);
        $endDate = new DateTime($discount->endDate);

        if ($today > $startDate && $today < $endDate) {
          // If a discount is found then apply it
          $this->price = $price - ($price * ($discount->discount / 100));
          return $this->setDiscount($discount->discount);
        }
      }

      $this->setDiscount(0);
    }

    public function getDiscount() {
      return $this->discount;
    }

    public function setDiscount($discount) {
      $this->discount = $discount;
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

    public function getQty() {
      return $this->qty;
    }

    public function setQty($qty) {
      $this->qty = $qty;
    }

    public function parse($raw) {
      $this->setID($raw->prodID);
      $this->setName($raw->prodName);
      $this->setImage($raw->image);
      $this->setBasePrice($raw->price);
      $this->setPrice($raw->price);
      $this->setPriceUnit($raw->priceUnit);
      $this->setDesc($raw->decript);
      $this->setQty($raw->quantity);
    }
  }

?>
