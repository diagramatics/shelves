<?php

class ProductsController extends Controller {

  public function index() {
    $model = $this->model('ProductsModel');

    // Let's get the categories
    $model->setCategories($this->database->getValues("Category", ""));

    // Then get the subcategories
    foreach ($model->getCategories() as $category) {
      $category->subs = $this->database->getValues("SubCategory", "", [
        ['catID', '=', $category->catID]
      ]);
    }

    $this->view('products/index', [
        'title' => 'Products',
        'categories' => $model->getCategories()
    ]);
  }

  public function category($id, $slug) {
    $model = $this->model('CategoryModel');
    // Get the category
    $category = $this->database->getValue("Category", "", [
      ['catID', '=', $id]
    ]);

    if (empty($category)) {
      return $this->view404();
    }

    $model->setCatID($category->catID);
    $model->setCatName($category->catName);

    // Then get the subcategories if there's any
    $category->subs = $this->database->getValues("SubCategory", "", [
      ['catID', '=', $category->catID]
    ]);

    $category->items = $this->database->getValues("Product", "", [
      ['catID', '=', $category->catID]
    ]);

    if (empty($category->subs) || empty($category->items)) {
      return $this->view404();
    }

    $model->setItems($category->items);

    $this->view('products/category', [
        'title' => $model->getCatName(),
        'catName' => $model->getCatName(),
        'items' => $model->getItems()
    ]);
  }

  public function subcategory($id, $slug) {
    $raw = $this->database->getValue("SubCategory", "", [
      ['subCatID', '=', $id]
    ]);

    if (empty($raw)) {
      return $this->view404();
    }

    $model = $this->model("SubCategoryModel");
    $model->setRaw($raw);

    $products = $this->database->getValues("Product", "", [
      ['subCatID', '=', $model->getID()]
    ]);

    if (empty($products)) {
      return $this->view404();
    }

    $this->view('products/category', [
      'title' => $model->getName(),
      'items' => $products
    ]);
  }

  public function product($productID, $productName) {
    $model = $this->model('ProductModel');
    // Put uppercase on the first letter
    $product = $this->database->getValue("Product", "", [
      ['prodID', '=', $productID]
    ]);

    if (!empty($product)) {
      $model->parse($product);
      $this->view('products/product', [
        'title' => $model->getName(),
        'product' => $model
      ]);
    }
    else {
      $this->view404();
    }
  }
}

?>
