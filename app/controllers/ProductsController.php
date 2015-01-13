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
    $model->setCatID($category->catID);
    $model->setCatName($category->catName);

    // Then get the subcategories if there's any
    $category->subs = $this->database->getValues("SubCategory", "", [
      ['catID', '=', $category->catID]
    ]);

    $category->items = $this->database->getValues("Product", "", [
      ['catID', '=', $category->catID]
    ]);

    $model->setItems($category->items);

    $this->view('products/category', [
        'title' => $model->getCatName(),
        'catName' => $model->getCatName(),
        'items' => $model->getItems()
    ]);
  }

  public function subcategory($id, $slug) {
    // TODO: Implement this
  }

  public function product($productID, $productName) {
    $model = $this->model('ProductModel');
    // Put uppercase on the first letter
    $product = $this->database->getValue("Product", "", [
      ['prodID', '=', $productID]
    ]);
    $model->setName($product->prodName);
    $model->setDesc($product->decript);

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/img/products/'.$product->image)) {
      $model->setImage('/img/products/' . $product->image);
    }
    else if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/img/products/'.$productID.'-'.$productName.'.jpg')) {
      $model->setImage('/img/products/' . $productID.'-'.$productName.'.jpg');
    }
    else if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/img/products/'.$productID.'-'.$productName.'.png')) {
      $model->setImage('/img/products/' . $productID.'-'.$productName.'.png');
    }
    else {
      $model->setImage('/img/products/default.jpg');
    }

    $model->setPrice($product->price);
    $model->setPriceUnit($product->priceUnit);
    $model->setQty($product->quantity);

    $this->view('products/product', [
      'title' => $model->getName(),
      'id' => $productID,
      'name' => $model->getName(),
      'desc' => $model->getDesc(),
      'image' => $model->getImage(),
      'price' => $model->getPrice(),
      'priceUnit' => $model->getPriceUnit(),
      'qty' => $model->getQty()
    ]);
  }
}

?>
