<?php

class BagController extends Controller {

  public function index() {
    $bag = $_SESSION["bag"];
    $ids = [];

    foreach($bag as $item) {
      array_push($ids, $item["id"]);
    }

    $products = $this->database->getValues("Product", "", [
      ['prodID', 'LIKE', '('. implode(',', $ids) .')']
    ]);

    $mapQuantity = function($k) use ($bag) {
      $i = 0;
      foreach($bag as $item) {
        if ($item["id"] === $k->prodID) {
          $k->bagQty = $item["qty"];
          return $k;
        }
        $i++;
      }
    };
    $products = array_map($mapQuantity, $products);

    $totalCost = 0.0;
    foreach($products as $product) {
      $totalCost .= $product->price * $product->bagQty;
    }


    $this->view("bag/index", [
      "title" => "Your Shopping Bag",
      "products" => $products,
      "totalCost" => $totalCost
    ]);
  }
}

?>
