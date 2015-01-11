<?php

class ProductsController extends Controller {

  public function index() {
    $model = $this->model('ProductsModel');

    // Let's get the categories
    $categories = $this->database->getValues("Category", "");

    $this->view('products/index', [
        'title' => 'Products'
    ]);
  }

  public function show($productID, $productName) {
    $model = $this->model('ProductModel');
    // Put uppercase on the first letter
    $model->name = ucfirst($productName);

    if (file_exists('/img/products/'.$productID.'-'.$productName.'.jpg')) {
      $model->image = $productID.'-'.$productName.'.jpg';
    }
    else if (file_exists('/img/products/'.$productID.'-'.$productName.'.png')) {
      $model->image = $productID.'-'.$productName.'.png';
    }
    else {
      $model->image = '/img/products/default.jpg';
    }

    $model->price = 12.34;
    $model->priceUnit = 'kg';

    $this->view('products/product', [
      'title' => $model->name,
      'id' => $productID,
      'name' => $model->name,
      'image' => $model->image,
      'price' => $model->price,
      'priceUnit' => $model->priceUnit
    ]);
  }
}

?>
