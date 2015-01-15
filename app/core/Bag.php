<?php

class Bag {
  public function __construct() {
    // Add items to the bag
    if (isset($_GET['addBag'])) {
      if ($_POST['qty'] >= $_POST['itemQty']) {
        return Helpers::makeAlert('bag', "Sorry, we don't have that much in stock. We currently have " . !empty($_POST['itemQty']) ? $_POST['itemQty'] : '0' . " left.");
      }

      // ----
      // If there's no errors then do these
      // ----
      if (!isset($_SESSION['bag'])) {
        $_SESSION['bag'] = [];
      }
      array_push($_SESSION['bag'], array(
        "id" => $_POST['itemID'],
        "qty" => $_POST['qty']
      ));

      Helpers::makeAlert('bag', 'Added item to bag.');
    }

    // Bag manipulating
    else if (isset($_GET['manipulateBag'])) {
      $id = $_POST['prodID'];
      $name = $_POST['prodName'];

      // Item removal
      if (isset($_POST['removeItem'])) {
        $i = 0;
        // Try and find the item and remove it if found
        foreach($_SESSION['bag'] as $bagItem) {
          if ($bagItem['id'] === $id) {
            unset($_SESSION['bag'][$i]);
            Helpers::makeAlert('bag', 'Item removed from bag.');
            // Found it! Now stop the loop and the function entirely.
            return;
          }
          $i++;
        }
        // If no reference of that item is found, then it's an error?
        Helpers::makeAlert('bag', 'There is a problem removing that item. Please try again.');
      }
    }
  }
}

?>
