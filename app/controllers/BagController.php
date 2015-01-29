<?php

class BagController extends Controller {

  public function index() {
    $model = $this->model('BagModel');
    $model->setProducts(null);
    $model->setTotalCost(0);

    if (!empty($_SESSION['bag'])) {
      $bag = $_SESSION["bag"];
      $ids = [];
      foreach($bag as $item) {
        array_push($ids, $item["id"]);
      }

      $products = $this->database->getValues("Product", "", [], [
        'WHERE prodID IN ('. implode(',', $ids) .')'
      ]);
      $productsFormatted = array();
      $i = 1;
      foreach ($products as $product) {
        $productsFormatted[$i] = $this->model('ProductModel');
        $productsFormatted[$i]->parse($product);
        $i++;
      }
      $model->setProducts($productsFormatted);

      $mapQuantity = function($k) use ($bag) {
        $i = 0;
        foreach($bag as $item) {
          if ($item["id"] === $k->getID()) {
            $k->setQty($item["qty"]);
            return $k;
          }
          $i++;
        }
      };
      $model->setProducts(array_map($mapQuantity, $model->getProducts()));

      $totalCost = 0.0;
      foreach($model->getProducts() as $product) {
        $totalCost += $product->getPrice() * $product->getQty();
      }
      $model->setTotalCost($totalCost);
    }

    $this->view("bag/index", [
      "title" => "Your Shopping Bag",
      "products" => $model->getProducts(),
      "totalCost" => $model->getTotalCost()
    ]);
  }
}

?>
