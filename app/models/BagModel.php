<?php

class BagModel extends Model {
  private $products;
  private $totalCost;

  public function parse($raw) {}
  public function extract() {
    return void;
  }

  public function getProducts() {
    return $this->products;
  }

  public function setProducts($bag) {
    // Make two arrays: one for quantity and one for product IDs
    $qtys = array();
    $ids = array();
    foreach($bag as $item) {
      array_push($ids, $item['id']);
      array_push($qtys, $item['qty']);
    }

    // Get the products according to the IDs first
    $products = $this->database->getValues("Product", "", [], [
      'WHERE prodID IN ('. implode(',', $ids) .')'
    ]);

    // Format the array so it uses an associative array with IDs as keys
    $productsFormatted = array();
    $i = 1;
    foreach ($products as $product) {
      // Since the products aren't ordered by the database query we can assume
      // the orders are the same as the product order in the bag
      $productsFormatted[$i] = array(
        'model' => $this->model("ProductModel"),
        'bagQty' => 0
      );
      $productsFormatted[$i]['model']->parse($product);
      $i++;
    }
    $products = $productsFormatted;

    $mapQuantity = function($k) use ($bag) {
      $i = 0;
      foreach($bag as $item) {
        if ($item["id"] === $k['model']->getID()) {
          $k['bagQty'] = $item["qty"];
          return $k;
        }
        $i++;
      }
    };
    $this->products = array_map($mapQuantity, $products);
  }

  public function getTotalCost() {
    return $this->totalCost;
  }

  public function setTotalCost($products) {
    $this->totalCost = 0;

    foreach($products as $product) {
      $this->totalCost += $product['model']->getPrice() * $product['bagQty'];
    }
  }
}

?>
