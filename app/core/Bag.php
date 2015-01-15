<?php

class Bag {
  public function __construct() {
    // Add items to the bag
    if (isset($_GET['addBag'])) {
      if ($_POST['qty'] > $_POST['itemQty']) {
        return Helpers::makeAlert('bag', "Sorry, we don't have that much in stock. We currently have " . !empty($_POST['itemQty']) ? $_POST['itemQty'] : '0' . " left.");
      }

      // ----
      // If there's no errors then do these
      // ----
      if (!isset($_SESSION['bag'])) {
        $_SESSION['bag'] = [];
      }

      // If there's the same item found on the bag, then add the quantities to it.
      if (isset($_SESSION['bag'][$_POST['itemID']])) {
        $_SESSION['bag'][$_POST['itemID']]['qty'] += $_POST['qty'];
        Helpers::makeAlert('bag', 'Added '. $_POST['qty'] .' more item to bag. There is now '. $_SESSION['bag'][$_POST['itemID']]['qty'] .' in your bag.');
      }
      // If not then add it
      else {
        $_SESSION['bag'][$_POST['itemID']] = array(
          "id" => $_POST['itemID'],
          "qty" => $_POST['qty']
        );
        Helpers::makeAlert('bag', 'Added item to bag.');
      }
    }

    // Bag manipulating
    else if (isset($_GET['manipulateBag'])) {
      $id = $_POST['prodID'];
      $name = $_POST['prodName'];

      // Item removal
      if (isset($_POST['removeItem'])) {
        // Try and find the item and remove it if found
        if (isset($_SESSION['bag'][$id])) {
          unset($_SESSION['bag'][$id]);
          Helpers::makeAlert('bag', 'Item removed from bag.');
        }
        // If no reference of that item is found, then it's an error?
        else {
          Helpers::makeAlert('bag', 'There is a problem removing that item. Please try again.');
        }
      }

      // Item quantity edit -- confirmation
      // Why confirmation? Because the normal one is just for toggling the input
      else if (isset($_POST['confirmEditItemQty'])) {
        $editedQty = $_POST['editedQty'];
        // Try and find the item and edit the quantity if found
        if (isset($_SESSION['bag'][$id])) {
          $_SESSION['bag'][$id]['qty'] = $editedQty;
          Helpers::makeAlert('bag', 'Quantity edited to '. $editedQty .'.');
        }
        // If no reference of that item is found, then it's an error?
        else {
          Helpers::makeAlert('bag', 'There is a problem removing that item. Please try again.');
        }
      }
    }
  }
}

?>
