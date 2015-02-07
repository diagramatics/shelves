<?php

class Bag {
  public function __construct() {
    // Add items to the bag
    if (isset($_GET['addBag'])) {
      $result = Bag::addItem();
      if ($result == 'nostock') {
        Helpers::makeAlert('bag', "Sorry, we don't have that much in stock. We currently have " . !empty($_POST['itemQty']) ? $_POST['itemQty'] : '0' . " left.");
      }
      else if ($result == 'add') {
        Helpers::makeAlert('bag', 'Added item to bag.');
      }
      else if ($result == 'error') {
        Helpers::makeAlert('bag', 'There is a problem. Please try again.');
      }
      else {
        Helpers::makeAlert('bag', 'Added '. $_POST['qty'] .' more item to bag. There is now '. $_SESSION['bag'][$_POST['itemID']]['qty'] .' in your bag.');
      }
    }
  }
  public static function addItem() {
    if ($_POST['qty'] > $_POST['itemQty'] || ($_SESSION['bag'][$_POST['itemID']]['qty'] + $_POST['qty']) > $_POST['itemQty']) {
      return 'nostock';
    }

    // ----
    // If there's no errors then do these

    // If there's no shopping bags yet, make one
    if (!isset($_SESSION['bag'])) {
      $_SESSION['bag'] = array();
    }

    // If there's the same item found on the bag, then add the quantities to it.
    if (isset($_SESSION['bag'][$_POST['itemID']])) {
      $_SESSION['bag'][$_POST['itemID']]['qty'] += $_POST['qty'];
      return ($_SESSION['bag'][$_POST['itemID']]['qty']);
    }
    // If not then add it
    else {
      $_SESSION['bag'][$_POST['itemID']] = array(
        "id" => $_POST['itemID'],
        "qty" => $_POST['qty']
      );
      return 'add';
    }

    // Return an error if anything else happens
    return 'error';
  }
}

?>
