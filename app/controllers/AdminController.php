<?php

class AdminController extends Controller {

  private function viewIfAllowed($view, $data) {
    if (isset($_SESSION["userLevel"]) && $_SESSION["userLevel"] == 1) {
      if ($view == 'admin/index') {
        $this->view($view, $data);
      }
      // Add the "back to admin panel" link for any other pages other than index
      else {
        $this->view(['admin/header', $view], $data);
      }
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
    if (isset($_GET['adminDeleteCategory'])) {
      $catID = $_POST['catID'];
      $query = $this->database->deleteValue("Category", [['catID', '=', $catID]]);

      if ($query) {
        Helpers::makeAlert("category", "Successfully deleted ".$_POST['catName']." category.");
      }
      else if ($_POST['products'] > 0) {
        Helpers::makeAlert("category", "There is still products assigned to ".$_POST['catName'].". Remove them first or assign to another category.");
      }
      else if ($_POST['subCats'] > 0) {
        Helpers::makeAlert("category", "There is still subcategories assigned to ".$_POST['catName'].". Remove them first or assign to another category.");
      }
      else {
        Helpers::makeAlert("category", "There is a problem in deleting ".$_POST['catName'].". Please try again.");
      }
    }

    $categories = $this->database->getValues("Category", "");
    $categoriesFormat = array();
    // Format the whole array so it becomes an associative array with IDs as the indicator
    foreach ($categories as $category) {
      $categoriesFormat[$category->catID] = $category;
      $categoriesFormat[$category->catID]->subCats = 0;
      $categoriesFormat[$category->catID]->products = 0;
    }
    $categories = $categoriesFormat;

    $subcategories = $this->database->getValues("SubCategory", "");
    foreach ($subcategories as $subcategory) {
      $categories[$subcategory->catID]->subCats++;
    }
    $products = $this->database->getValues("Product", "");
    foreach ($products as $product) {
      $categories[$product->catID]->products++;
    }

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

    if ($insert) {
      Helpers::makeAlert('adminCategory', "Successfully edited the category.");
    }
    else {
      Helpers::makeAlert('adminCategory', "There's something wrong with editing the category. Please try again.");
    }
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
        Helpers::makeAlert('adminCategory', "There's something wrong with adding the category. Please try again.");
      }
      else {
        $_POST['adminAddCategory'] = true;
        Helpers::makeAlert('adminCategory', "Successfully added to category list.");
      }
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
    if (isset($_GET['adminDeleteSubCategory'])) {
      $subCatID = $_POST['subCatID'];
      $query = $this->database->deleteValue("SubCategory", [['subCatID', '=', $subCatID]]);

      if ($query) {
        Helpers::makeAlert("category", "Successfully deleted ".$_POST['subCatName']." subcategory.");
      }
      else if ($_POST['products'] > 0) {
        Helpers::makeAlert("category", "There is still products assigned to ".$_POST['subCatName'].". Remove them first or assign to another category.");
      }
      else {
        Helpers::makeAlert("category", "There is a problem in deleting ".$_POST['subCatName'].". Please try again.");
      }
    }

    $subcategories = $this->database->getValues("SubCategory", "");
    $subcategoriesFormat = array();
    // Format this array also
    foreach ($subcategories as $subcategory) {
      $subcategoriesFormat[$subcategory->subCatID] = $subcategory;
      $subcategoriesFormat[$subcategory->subCatID]->products = 0;
    }
    $subcategories = $subcategoriesFormat;

    $products = $this->database->getValues("Product", "");
    foreach ($products as $product) {
      if (!empty($product->subCatID)) {
        $subcategories[$product->subCatID]->products++;
      }
    }

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
        Helpers::makeAlert('adminSubCategory', "There's something wrong with adding the subcategory. Please try again.");
      }
      else {
        Helpers::makeAlert('adminSubCategory', "Successfully added to subcategory list.");
      }
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

    if ($insert) {
      Helpers::makeAlert('adminSubCategory', "Successfully edited the category.");
    }
    else {
      Helpers::makeAlert('adminSubCategory', "There's something wrong with editing the category. Please try again.");
    }
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
          Helpers::makeAlert('product', 'Successfully deleted product.');
        }
        // If the deletion fails and the file is there get the product back
        else {
          $this->database->rollback();
          Helpers::makeAlert('product', "There's a problem in deleting the product image. Please try again.");
        }
        $this->database->autocommit(true);
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
      Helpers::makeAlert('adminProduct', "You have chosen a wrong subcategory — it's not a subcategory of the chosen category.");
      return $_POST['adminAddProduct'] = false;
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
    // Add these if they're not empty
    $subcategory[1] == 0 ?: array_push($dbValues, ['subCatID', $subcategory[1]]);
    $priceUnit == "" ?: array_push($dbValues, ['priceUnit', $priceUnit]);
    $description == "" ?: array_push($dbValues, ['decript', $description]);

    // Before inserting stop autocommit so we can rollback
    $this->database->autocommit(false);

    // Next try and insert the product to the database
    $insert = $this->database->insertValue("Product", $dbValues);

    // If something bad happens abort
    if ($insert == false) {
      Helpers::makeAlert('adminProduct', "There's something wrong with adding the product. Please try again.");
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
        $this->database->commit();
        $this->database->autocommit(true);
        Helpers::makeAlert('adminProduct', "Successfully added to product list.");
        return $_POST['adminAddProduct'] = true;
      }
    }

    // If the image upload fails then churn out an error and delete the database insert.
    Helpers::makeAlert('adminProduct', "The server is unable to accept the uploaded image. Try again later.");
    $_POST['adminAddProduct'] = 'noupload';
    $this->database->rollback();
    $this->database->autocommit(true);
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
      Helpers::makeAlert('adminProduct', "You have chosen a wrong subcategory — it's not a subcategory of the chosen category.");
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
      $this->database->autocommit(false);
      // Make the slug from the image
      $slug = Helpers::makeSlug($name);

      // And get the image
      $imageFile = $_FILES['image'];

      $imageName = $prodID . '-' . $slug . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);

      $update = true;
      // Upload the image
      // TODO: More image uploading validations
      if (!empty($imageFile) && move_uploaded_file($imageFile['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/img/products/' . $imageName)) {
        // We're done here. Update the image file reference and finish
        $update = $this->database->updateValue("Product", [['image', $imageName]], [['prodID', '=', $id]]);
        if (!$update) {
          Helpers::makeAlert('adminProduct', "The server is unable to accept the uploaded image. Try again later.");
          $_POST['adminAddProduct'] = 'noupload';
        }
        else {
          array_push($dbValues, ['image', $imageName]);
        }
      }
    }

    $insert = $this->database->updateValue("Product", $dbValues, [['prodID', '=', $prodID]]);
    if ($insert && $update) {
      Helpers::makeAlert('adminProduct', "Successfully edited the product.");
      $this->database->commit();
    }
    else {
      Helpers::makeAlert('adminProduct', "There's something wrong with editing the product. Please try again.");
      $this->database->rollback();
    }
    $this->database->autocommit(true);
    return $_POST['adminEditProduct'] = $insert;
  }

  public function specials($item1 = "", $item2 = "") {
    if ($item1 == "add") {
      $this->addSpecialView();
    }
    else if ($item1 == "edit") {
      $this->editSpecialView($item2);
    }
    else {
      $this->specialsView();
    }
  }

  private function specialsView() {
    if (isset($_GET['deleteSpecial'])) {
      $this->deleteSpecial($_POST['promotionID'], $_POST['promotionName']);
    }

    $specialsModel = array();
    $specialsRaw = $this->database->getValues("Promotion", "");
    if ($specialsRaw) {
      foreach ($specialsRaw as $s) {
        $model = $this->model("SpecialsModel");
        $model->parse($s);

        $productsRaw = $this->database->getValues("ProductPromotion", "", [
          ['promotionID', '=', $s->promotionID]
        ]);
        if (!empty($productsRaw)) {
          $model->linkProducts($productsRaw);
        }

        array_push($specialsModel, $model);
      }
    }

    $this->viewIfAllowed('admin/specials/index', [
      'title' => 'Users',
      'specials' => $specialsModel
    ]);
  }

  private function deleteSpecial($id, $name) {
    // Stop autocommit so we can rollback it in case of deletion problems
    $this->database->autocommit(false);

    $linkDelete = $this->database->deleteValue("ProductPromotion", [
      ['promotionID', '=', $id]
    ]);
    $promotionDelete = $this->database->deleteValue("Promotion", [
      ['promotionID', '=', $id]
    ]);

    if ($linkDelete && $promotionDelete) {
      // If it is successful then commit the changes
      $this->database->commit();
      Helpers::makeAlert('specials', 'Successfully deleted ' . $name . ' from your specials list.');
    }
    else {
      // If it fails then rollback
      $this->database->rollback();
      Helpers::makeAlert('specials', 'There is a problem in deleting the special. Please try again later.');
    }
    // Start autocommit again
    $this->database->autocommit(true);
  }

  private function addSpecialView() {
    if (isset($_GET["adminAddSpecial"]) && isset($_POST['confirmAddSpecial'])) {
      $this->addNewSpecial();
    }

    $products = $this->database->getValues("Product", "");
    $productsFormatted = array();
    foreach ($products as $product) {
      $model = $this->model("ProductModel");
      $model->parse($product);
      array_push($productsFormatted, $model);
    }

    $this->viewIfAllowed('admin/specials/add', [
      'title' => 'Add New Special',
      'products' => $productsFormatted
    ]);
  }

  private function addNewSpecial() {
    // TODO: PHP fallbacks
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $startDate = new DateTime($_POST['startDate']);
    $endDate = new DateTime($_POST['endDate']);
    $productsCount = $_POST['finalProductsCount'];

    // Stop autocommit so we can rollback
    $this->database->autocommit(false);
    $promotionInsert = $this->database->insertValue("Promotion", [
      ['promotionTitle', $title],
      ['promotionDesc', $desc],
      ['startDate', $startDate->format('Y-m-d')],
      ['endDate', $endDate->format('Y-m-d')]
    ]);
    $promotionID = $this->database->insert_id;
    $productsInsert = true;
    for ($i = 1; $i <= $productsCount; $i++) {
      if ($_POST['product' . $i] > 0) {
        if (!($this->database->insertValue("ProductPromotion", [
          ['promotionID', $promotionID],
          ['prodID', $_POST['product' . $i]],
          ['discount', $_POST['discount' . $i]]
        ]))) {
          $productsInsert = false;
          break;
        }
      }
    }

    if ($promotionInsert && $productsInsert) {
      $this->database->commit();
      Helpers::makeAlert('addSpecial', 'Successfully added special.');
    }
    else {
      $this->database->rollback();
      Helpers::makeAlert('addSpecial', 'There is a problem in adding the special. Please try again later.');
    }

    // We're finished. Enable it again.
    $this->database->autocommit(true);
  }

  private function editSpecialView($id) {
    if (isset($_GET["adminEditSpecial"]) && isset($_POST["confirmEditSpecial"])) {
      $this->editSpecial($id);
    }

    $specialModel = $this->model('SpecialsModel');
    $specialRaw = $this->database->getValue("Promotion", "", [
      ['promotionID', '=', $id]
    ]);
    if ($specialRaw) {
      $specialModel->parse($specialRaw);
      $productsRaw = $this->database->getValues("ProductPromotion", "", [
        ['promotionID', '=', $specialRaw->promotionID]
      ]);
      if (!empty($productsRaw)) {
        $specialModel->linkProducts($productsRaw);
      }
    }
    $products = $this->database->getValues("Product", "");
    $productsFormatted = array();
    foreach ($products as $product) {
      $model = $this->model("ProductModel");
      $model->parse($product);
      array_push($productsFormatted, $model);
    }

    $this->viewIfAllowed('admin/specials/edit', [
      'title' => 'Edit Special',
      'special' => $specialModel->extract(),
      'products' => $productsFormatted
    ]);
  }

  private function editSpecial($id) {
    // TODO: PHP fallbacks
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $startDate = new DateTime($_POST['startDate']);
    $endDate = new DateTime($_POST['endDate']);
    $productsCount = $_POST['finalProductsCount'];

    // Stop autocommit so we can rollback
    $this->database->autocommit(false);
    $promotionInsert = $this->database->updateValue("Promotion", [
      ['promotionTitle', $title],
      ['promotionDesc', $desc],
      ['startDate', $startDate->format('Y-m-d')],
      ['endDate', $endDate->format('Y-m-d')]
    ], [
      ['promotionID', '=', $id]
    ]);
    $productsInsert = true;

    // Get all the rows of the discounted stuff on the DB
    $products = $this->database->getValues("ProductPromotion", "", [
      ['promotionID', '=', $id]
    ]);
    // Make an assoc array of it
    $productsFormatted = array();
    foreach ($products as $product) {
      $productsFormatted[$product->prodID] = $product;
    }
    $products = $productsFormatted;

    for ($i = 1; $i <= $productsCount; $i++) {
      if ($_POST['product' . $i] > 0) {
        // If there is an existing record on the DB of that product then update it
        if (!empty($products[$_POST['product' . $i]])) {
          if (!($this->database->updateValue("ProductPromotion", [
            ['discount', $_POST['discount' . $i]]
          ], [
            ['promotionID', '=', $id],
            ['prodID', '=', $_POST['product' . $i]]
          ]))) {
            $productsInsert = false;
            break;
          }
          // Unset that value in that array if successful
          unset($products[$_POST['product' . $i]]);
        }
        // If not make a new one
        else {
          die('a');
          if (!($this->database->insertValue("ProductPromotion", [
            ['promotionID', $id],
            ['prodID', $_POST['product' . $i]],
            ['discount', $_POST['discount' . $i]]
          ]))) {
            $productsInsert = false;
            break;
          }
        }
      }
    }
    // Assume the products left on the $products are deleted now and remove them
    foreach ($products as $product) {
      if (!($this->database->deleteValue("ProductPromotion", [
        ['prodID', '=', $product->prodID]
      ]))) {
        $productsInsert = false;
        break;
      }
    }

    if ($promotionInsert && $productsInsert) {
      $this->database->commit();
      Helpers::makeAlert('addSpecial', 'Successfully edited special.');
    }
    else {
      $this->database->rollback();
      Helpers::makeAlert('addSpecial', 'There is a problem in editing the special. Please try again later.');
    }

    // We're finished. Enable it again.
    $this->database->autocommit(true);
  }

  public function users() {
    $users = $this->database->getValues("Account", "");
    $this->viewIfAllowed('admin/users/index', [
      'title' => 'Users',
      'users' => $users
    ]);
  }
}

?>
