<?php

class Bag {
  public function __construct() {
    if (isset($_GET['addBag'])) {
      if ($_POST['qty'] >= $_POST['itemQty']) {
        return Helpers::makeAlert('bag', "Sorry, we don't have that much in stock. We currently have " . $_POST['itemQty'] . " left.");
      }

      // ----
      // If there's no errors then do these
      // ----
      //if (!isset($_SESSION['bag'])) {
      $_SESSION['bag'] = [];
      //}
      array_push($_SESSION['bag'], array(
        "id" => $_POST['itemID'],
        "qty" => $_POST['qty']
      ));

      Helpers::makeAlert('bag', 'Added item to bag.');
    }
  }
}

?>
