<?php

class BagController extends Controller {

  public function index() {
    $model = $this->model('BagModel');

    if (!empty($_SESSION['bag'])) {
      $model->setProducts($_SESSION['bag']);
      $model->setTotalCost($model->getProducts());
    }

    $this->view("bag/index", [
      "title" => "Your Shopping Bag",
      "products" => $model->getProducts(),
      "totalCost" => $model->getTotalCost()
    ]);
  }

  public function checkout() {
    $model = $this->model('BagModel');

    if (!empty($_SESSION['bag'])) {
      $model->setProducts($_SESSION['bag']);
      $model->setTotalCost($model->getProducts());
    }
    else {
      // Redirect to the shopping bag if checkout has nothing on the bag
      header("Location: /bag");
      die();
    }
  }
}

?>
