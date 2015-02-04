<?php

class SearchController extends Controller {
  public function index() {
    $search = !empty($_GET['q']) ? $_GET['q'] :  '';
    $offset = (!empty($_GET['n']) ? $_GET['n'] : 0);

    // Query the result according to the search query and the offset
    $query = $this->database->getValues("Product", "", [
      ['prodName', 'LIKE', '%'.$search.'%']
    ], [
      'ORDER BY prodName ASC LIMIT 20 OFFSET '.$offset  * 20
    ]);
    $count = $this->database->getValue("Product", ["COUNT(prodName) AS count"], [
      ['prodName', 'LIKE', '%'.$search.'%']
    ], [
      'ORDER BY prodName'
    ]);

    // Make models out of the results
    $products = array();
    foreach ($query as $product) {
      $model = $this->model('ProductModel');
      $model->parse($product);
      array_push($products, $model);
    }

    // Now show the search result page
    $this->view('search/index', [
      'title' => 'Search Results',
      'q' => $search,
      'n' => $offset,
      'results' => $products,
      'count' => $count->count
    ]);
  }
}

?>
