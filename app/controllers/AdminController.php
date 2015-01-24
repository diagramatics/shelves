<?php

class AdminController extends Controller {

  private function viewIfAllowed($view, $data) {
    if (isset($_SESSION["userLevel"]) && $_SESSION["userLevel"] == 1) {
      $this->view($view, $data);
    }

    else {
      $this->view404();
    }
  }

  public function index() {
    $this->viewIfAllowed('admin/index', [
      'title' => 'Admin Panel'
    ]);
  }

  // Category
  public function category($item = "", $item2 = "") {
    if ($item == "add") {
      $this->addCategoryView();
    }
    elseif ($item == "edit") {
      $this->editCategoryView($item2);
    }
    else {
      $this->categoryView();
    }
  }

  private function categoryView() {
    $categories = $this->database->getValues("Category", "");

    $this->viewIfAllowed('admin/category/index', [
      'title' => 'Categories',
      'categories' => $categories
    ]);
  }

  private function editCategoryView($id) {
    $model = $this->model('CategoryModel');

    if (isset($_GET['adminEditCategory'])) {
      $this->editCategory($id);
    }

    if (!empty($id)) {
      $query = $this->database->getValue("Category", "", [
        ['catID', '=', $id]
      ]);

      if ($query) {
        $model->setCatName($query->catName);

        if (empty($_POST['adminEditCategory'])) {
          $_POST['adminEditCategory'] = 'in';
        }
      }
    }
    else {
      $_POST['adminEditCategory'] = 'stumbled';
    }

    $this->viewIfAllowed('admin/category/edit', [
      'title' => 'Edit Category',
      'name' => $model->getCatName()
    ]);
  }

  private function editCategory($catID) {
    $name = $_POST['name'];

    $insert = $this->database->updateValue("Category", [['catName', $name]], [['catID', '=', $catID]]);

    return $_POST['adminEditCategory'] = $insert;
  }

  private function addCategoryView() {
    if (isset($_GET["adminAddCategory"])) {
      $name = $_POST['name'];

      // Insert the category to the database
      $insert = $this->database->insertValue("Category", [['catName', $name]]);

      // If something bad happens abort
      if ($insert == false) {
        $_POST['adminAddCategory'] = false;
      }
      else $_POST['adminAddCategory'] = true;
    }

    $this->viewIfAllowed('admin/category/add', [
      'title' => 'Add New Category'
    ]);
  }

  // Subcategories
  public function subcategory($item = "", $item2 = "") {
    if ($item == "add") {
      $this->addSubCategoryView();
    }

    elseif ($item == "edit") {
      $this->editSubCategoryView($item2);
    }

    else {
      $this->subCategoryView();
    }
  }

  private function subCategoryView() {
    $subcategories = $this->database->getValues("SubCategory", "");
    $categories = $this->database->getValues("Category", "");

    $categoriesFormat = array();
    // Format the whole array so it becomes an associative array with IDs as the indicator
    foreach ($categories as $category) {
      $categoriesFormat[$category->catID] = $category;
    }
    $categories = $categoriesFormat;

    $this->viewIfAllowed('admin/subcategory/index', [
      'title' => 'Subcategories',
      'categories' => $categories,
      'subcategories' => $subcategories
    ]);
  }

  private function addSubCategoryView() {
    if (isset($_GET["adminAddSubCategory"])) {
      $name = $_POST['name'];
      $category = $_POST['category'];

      // Insert the category to the database
      $insert = $this->database->insertValue("SubCategory", [['subCatName', $name], ['catID', $category]]);

      // If something bad happens abort
      if ($insert == false) {
        $_POST['adminAddSubCategory'] = false;
      }
      else $_POST['adminAddSubCategory'] = true;
    }

    $categories = $this->database->getValues("Category", "");

    $this->viewIfAllowed('admin/subcategory/add', [
      'title' => 'Add New Subcategory',
      'categories' => $categories
    ]);
  }

  private function editSubCategoryView($id) {
    $name = "";
    $category = "";

    if (isset($_GET['adminEditSubCategory'])) {
      $this->editSubCategory($id);
    }

    if (!empty($id)) {
      $categories = $this->database->getValues("Category", "");

      $query = $this->database->getValue("SubCategory", "", [
        ['subCatID', '=', $id]
      ]);

      if ($query) {
        $name = $query->subCatName;
        $category = $query->catID;

        if (empty($_POST['adminEditSubCategory'])) {
          $_POST['adminEditSubCategory'] = 'in';
        }
      }
    }
    else {
      $_POST['adminEditSubCategory'] = 'stumbled';
    }

    $this->viewIfAllowed('admin/subcategory/edit', [
      'title' => 'Add New Subcategory',
      'categories' => $categories,
      'name' => $name,
      'category' => $category
    ]);
  }

  private function editSubCategory($subCatID) {
    $name = $_POST["name"];
    $catID = $_POST["category"];

    $insert = $this->database->updateValue("SubCategory", [['subCatName', $name], ['catID', $catID]], [['subCatID', '=', $subCatID]]);

    return $_POST['adminEditSubCategory'] = $insert;
  }

  // Products
  public function product($item = "", $item2 = "") {
    if ($item == "add") {
      $this->addProductView();
    }

    else if ($item == "edit") {
      $this->editProductView($item2);
    }

    else {
      $this->productsView();
    }
  }

  private function productsView() {
    // If is deleting
    if (isset($_GET['deleteProduct'])) {
      $prodID = $_POST['prodID'];

      $product = $this->database->getValue("Product", ["image"], [["prodID", "=", $prodID]]);

      // Stop autocommit so we can rollback it in case of deletion problems
      $this->database->autocommit(false);
      $deletion = $this->database->deleteValue("Product", [["prodID", "=", $prodID]]);

      // Check if the deletion succeeds
      if ($deletion) {
        // Now try to delete the image
        // Try delete it first. If it fails then check if the file is even there
        // If the file isn't there assume deletion is successful
        if (unlink($_SERVER['DOCUMENT_ROOT'] . '/img/products/' . $product->image) ||
        !file_exists($_SERVER['DOCUMENT_ROOT'] . '/img/products/' . $product->image)) {
          $this->database->commit();
          $this->database->autocommit(true);
          Helpers::makeAlert('product', 'Successfully deleted product.');
        }
        // If the deletion fails and the file is there get the product back
        else {
          $this->database->rollback();
          $this->database->autocommit(true);
          Helpers::makeAlert('product', "There's a problem in deleting the product image. Please try again.");
        }
      }
      else {
      // If even SQL deletion fails...
        Helpers::makeAlert('product', "There's a problem in deleting the product. Please try again.");
      }
    }

    $products = $this->database->getValues("Product", "");

    $categories = $this->database->getValues("Category", "");
    $categoriesFormat = array();
    // Format the whole array so it becomes an associative array with IDs as the indicator
    foreach ($categories as $category) {
      $categoriesFormat[$category->catID] = $category;
    }
    $categories = $categoriesFormat;

    $subcategories = $this->database->getValues("SubCategory", "", [], ['ORDER BY catID']);
    $subcategoriesFormat = array();
    // Format this array also
    foreach ($subcategories as $subcategory) {
      $subcategoriesFormat[$subcategory->subCatID] = $subcategory;
    }
    $subcategories = $subcategoriesFormat;

    $this->viewIfAllowed('admin/product/index', [
      'title' => 'Products',
      'products' => $products,
      'categories' => $categories,
      'subcategories' => $subcategories
    ]);
  }

  private function addProductView() {
    if (isset($_GET["adminAddProduct"])) {
      $this->addNewProduct();
    }

    $categories = $this->database->getValues("Category", "");
    $categoriesFormat = array();

    // Format the whole array so it becomes an associative array with IDs as the indicator
    foreach ($categories as $category) {
      $categoriesFormat[$category->catID] = $category;
    }
    $categories = $categoriesFormat;

    $subcategories = $this->database->getValues("SubCategory", "", [], ['ORDER BY catID']);

    $this->viewIfAllowed('admin/product/add', [
      'title' => 'Add New Product',
      'categories' => $categories,
      'subcategories' => $subcategories
    ]);
  }

  private function addNewProduct() {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $priceUnit = $_POST['priceUnit'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $subcategory = explode("-", $_POST['subcategory']); // Array index 0 is the catID, index 1 is subCatID
    $description = $_POST['description'];

    // Check if the subcategory is indeed a subcategory of the chosen category
    // This is a PHP fallback if no JS
    if ($subcategory[0] != '0' && $subcategory[0] != $category) {
      return $_POST['adminAddProduct'] = 'wrongsub';
    }

    // Make the slug from the image
    $slug = Helpers::makeSlug($name);

    // And get the image
    $imageFile = $_FILES['image'];

    $dbValues = [
      ['prodName', $name],
      ['price', $price],
      ['catID', $category],
      ['quantity', $quantity]
    ];

    // Add these two if they're not empty
    $subcategory[1] == 0 ?: array_push($dbValues, ['subCatID', $subcategory[1]]);
    $priceUnit == "" ?: array_push($dbValues, ['priceUnit', $priceUnit]);
    $description == "" ?: array_push($dbValues, ['decript', $description]);

    // Next try and insert the product to the database
    $insert = $this->database->insertValue("Product", $dbValues);

    // If something bad happens abort
    if ($insert == false) {
      return $_POST['adminAddProduct'] = false;
    }

    // Get the ID
    $id = $this->database->insert_id;
    // Make the image name from the ID and slug and the extension
    $imageName = $id . '-' . $slug . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);

    // Upload the image
    // TODO: More image uploading validations
    if (move_uploaded_file($imageFile['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/img/products/' . $imageName)) {
      // We're done here. Update the image file reference and finish
      $update = $this->database->updateValue("Product", [['image', $imageName]], [['prodID', '=', $id]]);
      if ($update) {
        return $_POST['adminAddProduct'] = true;
      }
    }

    // If the image upload fails then churn out an error and delete the database insert.
    $_POST['adminAddProduct'] = 'noupload';
    $this->database->deleteValue('Product', [['prodID', '=', $id]]);
  }

  private function editProductView($prodID) {
    $model = $this->model('ProductModel');
    $categories = array();
    $subcategories = array();

    // Check if the form is submitted
    if (isset($_GET['adminEditProduct'])) {
      $this->editProduct($prodID);
    }

    // Check if the user landed on this page incidentally
    if (!empty($prodID)) {

      $query = $this->database->getValue("Product", "", [
        ['prodID', '=', $prodID]
      ]);

      if ($query) {
        $model->setID($query->prodID);
        $model->setName($query->prodName);
        $model->setImage($query->image);
        $model->setPrice($query->price);
        $model->setPriceUnit($query->priceUnit);
        $model->setDesc($query->decript);
        $model->setQty($query->quantity);
        if (empty($_POST['adminEditProduct'])) {
          $_POST['adminEditProduct'] = 'in';
        }
      }

      $categories = $this->database->getValues("Category", "");
      $categoriesFormat = array();
      // Format the whole array so it becomes an associative array with IDs as the indicator
      foreach ($categories as $category) {
        $categoriesFormat[$category->catID] = $category;
      }
      $categories = $categoriesFormat;

      $subcategories = $this->database->getValues("SubCategory", "", [], ['ORDER BY catID']);
      $subcategoriesFormat = array();
      // Format this array also
      foreach ($subcategories as $subcategory) {
        $subcategoriesFormat[$subcategory->subCatID] = $subcategory;
      }
      $subcategories = $subcategoriesFormat;
    }

    else {
      $_POST['adminEditProduct'] = 'stumbled';
    }

    $this->viewIfAllowed('admin/product/edit', [
      'title' => 'Edit Product',
      'id' => $prodID,
      'name' => $model->getName(),
      'desc' => $model->getDesc(),
      'image' => $model->getImage(),
      'price' => $model->getPrice(),
      'priceUnit' => $model->getPriceUnit(),
      'qty' => $model->getQty(),
      'categories' => $categories,
      'subcategories' => $subcategories
    ]);
  }

  private function editProduct($prodID) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $priceUnit = $_POST['priceUnit'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $subcategory = explode("-", $_POST['subcategory']); // Array index 0 is the catID, index 1 is subCatID
    $description = $_POST['description'];

    if ($subcategory[0] != '0' && $subcategory[0] != $category) {
      return $_POST['adminEditProduct'] = 'wrongsub';
    }

    $dbValues = [
      ['prodName', $name],
      ['price', $price],
      ['quantity', $quantity],
      ['priceUnit', $priceUnit],
      ['decript', $description],
      ['catID', $category]
    ];

    if ($subcategory[1] != 0) {
      array_push($dbValues, ['subCatID', $subcategory[1]]);
    }

    // If there's any image uploaded to replace the existing image
    if (isset($_FILES['image'])) {
      // Make the slug from the image
      $slug = Helpers::makeSlug($name);

      // And get the image
      $imageFile = $_FILES['image'];

      $imageName = $prodID . '-' . $slug . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);

      // Upload the image
      // TODO: More image uploading validations
      if (move_uploaded_file($imageFile['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/img/products/' . $imageName)) {
        // We're done here. Update the image file reference and finish
        $update = $this->database->updateValue("Product", [['image', $imageName]], [['prodID', '=', $id]]);
        if (!$update) {
          return $_POST['adminAddProduct'] = 'noupload';
        }
        else {
          array_push($dbValues, ['image', $imageName]);
        }
      }
    }

    $insert = $this->database->updateValue("Product", $dbValues, [['prodID', '=', $prodID]]);
    return $_POST['adminEditProduct'] = $insert;
  }
}

?>
