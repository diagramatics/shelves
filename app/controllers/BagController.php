<?php

class BagController extends Controller {

  public function index() {
    $model = $this->model('BagModel');

    // Bag manipulating
    if (isset($_GET['manipulateBag'])) {
      $id = $_POST['prodID'];
      $name = $_POST['prodName'];

      // Item removal
      if (isset($_POST['removeItem'])) {
        $this->removeItem($id, $name);
      }

      // Item quantity edit -- confirmation
      // Why confirmation? Because the normal one is just for toggling the input
      else if (isset($_POST['confirmEditItemQty'])) {
        $editedQty = $_POST['editedQty'];
        $this->editItemQty($id, $name, $editedQty);
      }
    }

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

    if (empty($_SESSION['bag'])) {
      // Redirect to the shopping bag if checkout has nothing on the bag
      header("Location: /bag");
      die();
    }

    if (empty($_SESSION["userID"])) {
      Helpers::makeAlert("checkout", "Before being able to checkout you will need to register an account first.");
      $_POST['register'] = false;
      $this->index();
    }
    else {
      $model->setProducts($_SESSION['bag']);
      $model->setTotalCost($model->getProducts());

      $checkout = false;

      // Check if user is confirming checkout
      if (isset($_GET['processCheckout']) && isset($_POST['doCheckout'])) {
        $checkout = $this->processCheckout($model);
      }
      // Or check if the user is trying to add new address
      if (isset($_GET['processCheckout']) && isset($_POST['confirmAddAddress'])) {
        $this->addAddress();
      }

      if ($checkout == false) {
        $addresses = $this->database->getValues("Address", "", [
          ["userID", '=', $_SESSION["userID"]]
        ]);

        $this->view("bag/checkout", [
          "title" => "Checkout",
          "bag" => $model,
          "addresses" => $addresses
        ]);
      }
    }
  }

  private function processCheckout($bag) {
    $today = new DateTime(null);

    $addOrderResult = $this->database->insertValue("OrderBag", [
      ['userID', $_SESSION['userID']],
      ['totalCharge', $bag->getTotalCost()],
      ['dateMade', $today->format('Y-m-d')],
      ['addressID', $_POST['address']]
    ]);

    if (!$addOrderResult) {
      Helpers::makeAlert('checkout', 'There is a problem in checking out right now. Please try again later.');
      return false;
    }

    // Empty the bag if checkout succeeds
    unset($_SESSION['bag']);
    $this->view("bag/checkout-done", [
      "title" => "Checkout Done!"
    ]);

    return true;
  }

  private function addAddress() {
    $addAddressResult = $this->database->insertValue("Address", [
      ['userID', $_SESSION['userID']],
      ['unit', $_POST['addressUnit']],
      ['streetNo', $_POST['addressNumber']],
      ['streetName', $_POST['addressName']],
      ['street', $_POST['addressType']],
      ['city', $_POST['addressCity']],
      ['postcode', $_POST['addressPostcode']],
      ['state', $_POST['addressState']],
      ['primaryAddress', 0]
    ]);
    // Return true if successful, else return false
    if ($addAddressResult) {
      Helpers::makeAlert("accountSettings", "New address added.");
    }
    else {
      Helpers::makeAlert("accountSettings", "There's something wrong when adding a new address. Please try again.");
    }
  }

  private function removeItem($id, $name) {
    // Try and find the item and remove it if found
    if (isset($_SESSION['bag'][$id])) {
      unset($_SESSION['bag'][$id]);
      Helpers::makeAlert('bag', $name .' removed from bag.');
    }
    // If no reference of that item is found, then it's an error?
    else {
      Helpers::makeAlert('bag', 'There is a problem removing '. $name .'. Please try again.');
    }
  }

  private function editItemQty($id, $name, $editedQty) {
    // Try and find the item and edit the quantity if found
    if (isset($_SESSION['bag'][$id])) {
      $_SESSION['bag'][$id]['qty'] = $editedQty;
      Helpers::makeAlert('bag', 'Quantity of '. $name .' edited to '. $editedQty .'.');
    }
    // If no reference of that item is found, then it's an error?
    else {
      Helpers::makeAlert('bag', 'There is a problem changing the quantity of '. $name .'. Please try again.');
    }
  }



  // AJAX functions

  /**
    * AJAX request to call the view of the quantity edit form toggle
    */
  public function ajaxChangeItemQuantityBefore() {
    if (Helpers::isAjax()) {

      $productID = $_POST['confirmEditItemQty'];
      $qty = $_POST['editedQty'];
      $productModel = $this->model('ProductModel');
      $productModel->setID($productID);

      die(Helpers::ajaxReturnContent('../app/views/bag/index-edit-quantity-before.php', array(
        'product' => array(
          'model' => $productModel,
          'bagQty' => $qty
        )
      )));
    }
  }


  /**
    * AJAX request to call the view of the edit quantity input
    */
  public function ajaxChangeItemQuantity() {
    if (Helpers::isAjax()) {
      $productID = $_POST['editItemQty'];
      $qty = $_POST['editItemQtyAmount'];
      $productModel = $this->model('ProductModel');
      $query = $this->database->getValue("Product", "", [
        ['prodID', '=', $productID]
      ]);
      $productModel->parse($query);
      die(Helpers::ajaxReturnContent('../app/views/bag/index-edit-quantity.php', array(
        'product' => array(
          'model' => $productModel,
          'bagQty' => $qty
        )
      )));
    }
  }

  /**
    * AJAX request to change the quantity and returns the alert popup
    */
  public function ajaxConfirmChangeItemQuantity() {
    if (Helpers::isAjax()) {
      $productID = $_POST['confirmEditItemQty'];
      $qty = $_POST['editedQty'];

      if (isset($_SESSION['bag'][$productID])) {
        if ($_SESSION['bag'][$productID]['qty'] == $qty) {
          die('same');
        }

        $_SESSION['bag'][$productID]['qty'] = $qty;
        die('ok');
      }

      // If anything fails this code gets executed since die() stops the
      // execution entirely.
      die('error');
    }
  }

    /**
      * AJAX request to remove the item from the bag
      */
  public function ajaxRemoveItem() {
    if (Helpers::isAjax()) {
      $id = $_POST['prodID'];
      if (isset($_SESSION['bag'][$id])) {
        unset($_SESSION['bag'][$id]);
        die('ok');
      }

      die('error');
    }
  }

}

?>
