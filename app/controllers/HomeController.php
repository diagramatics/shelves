<?php

class HomeController extends Controller {
  public function index() {
    $today = new DateTime(null);

    $specials = array();
    $specialsRaw = $this->database->getValues("Promotion", "", [
      ['startDate', '<', $today->format('Y/m/d')]
    ]);
    if (!empty($specialsRaw)) {
      foreach ($specialsRaw as $special) {
        $model = $this->model("SpecialsModel");
        $model->parse($special);

        $productsRaw = $this->database->getValues("ProductPromotion", "", [
          ['promotionID', '=', $special->promotionID]
        ]);
        if (!empty($productsRaw)) {
          $model->linkProducts($productsRaw);
        }
        array_push($specials, $model);
      }
    }

    $products = $this->database->getValues("Product", "", [], [
      'ORDER BY RAND() LIMIT 25'  
    ]);
    $productsFormat = array();
    $productsAssoc = array();
    // Format the whole array so it becomes an associative array with IDs as the indicator
    foreach ($products as $product) {
      $model = $this->model("ProductModel");
      $model->parse($product);
      $productsAssoc[$model->getID()] = $model;
      array_push($productsFormat, $model);
    }
    $products = $productsFormat;

    $this->view('home/index', [
      'title' => 'Home',
      'specials' => $specials,
      'products' => $products,
      'productsAssoc' => $productsAssoc
    ]);
  }
}

?>
