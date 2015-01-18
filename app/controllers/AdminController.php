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
  public function category($item) {
    if ($item == "add") {
      $this->addCategory();
    }
  }

  private function addCategory() {
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
  public function subcategory($item) {
    if ($item == "add") {
      $this->addSubCategory();
    }
  }

  private function addSubCategory() {
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

  // Products
  public function product($item) {
    if ($item == "add") {
      $this->addProduct();
    }
  }


  private function addProduct() {
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
      ['quantity', $quantity],
    ];

    // Add these two if they're not empty
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
      // We're done here.
      return $_POST['adminAddProduct'] = true;
    }

    // If the image upload fails then churn out an error and delete the database insert.
    $_POST['adminAddProduct'] = 'noupload';
    $this->database->deleteValue('Product', [['prodID', '=', $id]]);
  }
}

?>
