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
}

?>
