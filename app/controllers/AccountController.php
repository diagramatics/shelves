<?php

class AccountController extends Controller {
  /**
   * Show the not logged in view when the user is not logged in
   */
  private function notLoggedInView() {
    return $this->view("account/not-logged-in", ["title" => "Whoops."]);
  }

  public function index() {
    $ordersOffset = empty($_GET['oN']) ? 0 : $_GET['oN'];

    $accountModel = $this->model('AccountModel');
    // Check if the user is logged in to an account
    if ($accountModel->isLoggedIn()) {
      $ordersRaw = $this->database->getValues("OrderBag", "", array(
        ['userID', '=', $_SESSION['userID']]
      ), array(
        "ORDER BY dateMade DESC, orderBagID ASC LIMIT 20 OFFSET ".$ordersOffset  * 20
      ));
      $ordersCount = $this->database->getValue("OrderBag", ["COUNT(OrderBagID) AS count"], array(
        ['userID', '=', $_SESSION['userID']]
      ), array(
        "ORDER BY dateMade DESC, orderBagID ASC"
      ));

      $orders = array();
      foreach ($ordersRaw as $orderRaw) {
        $orderModel = $this->model('OrderModel');
        $orderModel->parse($orderRaw);
        array_push($orders, $orderModel->extract());
      }

      $this->view("account/index", array(
        'title' => 'Your Account Overview',
        'orders' => $orders,
        'oN' => $ordersOffset,
        'oCount' => $ordersCount->count
      ));
    }
    else {
      $this->notLoggedInView();
    }
  }
  // AJAX calls in index
  public function ajaxOrderDetailsBefore() {
    if (Helpers::isAjax()) {
      $values = array(
        'order' => array(
          'id' => $_POST['detailsID']
        )
      );
      die(Helpers::ajaxReturnContent('../app/views/account/order-details-before.php', $values));
    }
  }
  public function ajaxOrderDetailsAfter() {
    if (Helpers::isAjax()) {
      $values = array(
        'order' => array(
          'id' => $_POST['detailsID']
        )
      );
      die(Helpers::ajaxReturnContent('../app/views/account/order-details-after.php', $values));
    }
  }
  public function ajaxOrderDetailsTable() {
    if (Helpers::isAjax()) {
      $orderRaw = $this->database->getValue("OrderBag", "", array(
        ['userID', '=', $_SESSION['userID']],
        ['orderBagID', '=', $_POST['detailsID']]
      ));
      if ($orderRaw) {
        $orderModel = $this->model('OrderModel');
        $orderModel->parse($orderRaw);
        die(Helpers::ajaxReturnContent('../app/views/account/order-details-table.php', array(
          'order' => $orderModel->extract()
        )));
      }
      else {
        die("notfound");
      }
    }
  }
  //---

  public function settings() {
    if(isset($_SESSION["email"])) {
      if (isset($_GET["changeAccountSettings"])) {
        $this->changeAccountSettings();
      }

      $model = $this->model("AccountModel");

      $account = $this->database->getValue("Account", "", [
        ["email", "=", $_SESSION["email"]]
      ]);

      $model->setID($account->userID);
      $model->setEmail($account->email);
      $model->setFName($account->fName);
      $model->setLName($account->lName);

      $addresses = $this->database->getValues("Address", "", [
        ["userID", "=", $model->getID()]
      ]);

      $this->view("account/settings", [
        "title" => "Account Settings",
        "email" => $model->getEmail(),
        "fName" => $model->getFName(),
        "lName" => $model->getLName(),
        "addresses" => $addresses
      ]);
    }
    else {
      $this->notLoggedInView();
    }
  }

  /**
    * Change the account settings
    */
  private function changeAccountSettings() {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];

    // Add a new address
    if (isset($_POST['confirmAddAddress'])) {
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

    // Delete an address
    else if (isset($_POST['deleteAddress'])) {
      $deleteAddressResult = $this->database->deleteValue("Address", [['addressID', '=', $_POST['deleteAddress']]]);
      if ($deleteAddressResult) {
        Helpers::makeAlert("accountSettings", "Address deleted.");
      }
      else {
        Helpers::makeAlert("accountSettings", "There's something wrong when deleting that address. Please try again.");
      }
    }

    // Change an address to primary
    else if (isset($_POST['changeAddressPrimary'])) {
      // Stop autocommit so we can rollback it in case of deletion problems
      $this->database->autocommit(false);
      $addressResetResult = $this->database->updateValue("Address", [
        ["primaryAddress", "0"]
      ], [
        ["userID", "=", $_SESSION['userID']]
      ]);

      $addressUpdateResult = $this->database->updateValue("Address", [
        ["primaryAddress", "1"]
      ], [
        ["addressID", "=", $_POST['changeAddressPrimary']]
      ]);

      if ($addressResetResult && $addressUpdateResult) {
        $this->database->commit();
        Helpers::makeAlert("accountSettings", "Address has been set to primary.");
      }
      else {
        $this->database->rollback();
        Helpers::makeAlert("accountSettings", "There is something wrong in updating your primary address. Please try again.");
      }
      $this->database->autocommit(true);
    }

    // Change password
    else if (isset($_POST['confirmChangePassword'])) {
      $oldPassword = $_POST['password'];
      $newPassword = $_POST['newPassword'];

      $credentials = $this->database->getValue("Login", "", [
        ['userID', '=', $_SESSION['userID']]
      ]);

      // If the validations fail make sure the change password dialog is still open
      $_POST['changePassword'] = true;
      // Let's try and validate everything now
      // If the credentials fetch failed abort the whole process
      if (!$credentials) {
        return Helpers::makeAlert("accountSettings", "There is something wrong in updating your password. Please try again.");
      }
      // If the current password didn't match what we have on the server then abort
      if (!password_verify($oldPassword, $credentials->password)) {
        return Helpers::makeAlert("accountSettings", "Your current password is wrong. Please try again.");
      }
      // If the old and new password is the same abort the whole process
      // There's no point in changing the password
      if ($oldPassword == $newPassword) {
        return Helpers::makeAlert("accountSettings", "Your new password is the same with the current one.");
      }
      // If the new password doesn't match the confirmed password also abort
      if ($newPassword !== $_POST['confirmPassword']) {
        return Helpers::makeAlert("accountSettings", "The new password doesn't match with the one you confirmed. Please try again.");
      }

      // The code below gets executed if it passes all the validations
      $changePassword = $this->changePassword($newPassword);
      if ($changePassword) {
        // Password changing is successful. Make sure the password change dialog is closed
        unset($_POST['changePassword']);
        return Helpers::makeAlert("accountSettings", "Password successfully updated.");
      }
      else {
        return Helpers::makeAlert("accountSettings", "There is something wrong in updating your password. Please try again.");
      }
    }

    // If it is not some minor interaction with the form then update the whole thing
    else if (isset($_POST['changeSettings'])) {
      $profileUpdateResult = $this->database->updateValue("Account", [
        ["fName", $fName],
        ["lName", $lName]
      ], [
        ["email", "=", $_SESSION['email']]
      ]);



      if ($profileUpdateResult) {
        $this->database->commit();
        Helpers::makeAlert("accountSettings", "You account settings has been updated.");
      }
      else {
        Helpers::makeAlert("accountSettings", "There is something wrong in updating your account settings. Please try again.");
      }
    }
  }

  private function changePassword($newPassword) {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $changePassword = $this->database->updateValue("Login", [["password", $hash]], [
      ['userID', '=', $_SESSION['userID']]
    ]);
    return $changePassword;
  }

  // Ajax-specific stuff
  public function ajaxAddAddress() {
    die(Helpers::ajaxReturnContent('../app/views/account/settings-add-address.php'));
  }
  public function ajaxDeleteAddress() {
    if (Helpers::isAjax()) {
      $deleteAddressResult = $this->database->deleteValue("Address", [['addressID', '=', $_POST['deleteAddress']]]);
      if ($deleteAddressResult) {
        die('ok');
      }
      else {
        die('error');
      }
    }
  }
  public function ajaxChangePrimaryAddress() {
    if (Helpers::isAjax()) {
      // Stop autocommit so we can rollback it in case of deletion problems
      $this->database->autocommit(false);
      $addressResetResult = $this->database->updateValue("Address", [
        ["primaryAddress", "0"]
      ], [
        ["userID", "=", $_SESSION['userID']]
      ]);

      $addressUpdateResult = $this->database->updateValue("Address", [
        ["primaryAddress", "1"]
      ], [
        ["addressID", "=", $_POST['changeAddressPrimary']]
      ]);

      $return = 'error';
      if ($addressResetResult && $addressUpdateResult) {
        $this->database->commit();
        $return = 'ok';
      }
      else {
        $this->database->rollback();
        $return = 'error';
      }
      $this->database->autocommit(true);
      die($return);
    }
  }
  public function ajaxChangePassword() {
    die(Helpers::ajaxReturnContent('../app/views/account/settings-change-password.php'));
  }
  public function ajaxChangePasswordButton() {
    die(Helpers::ajaxReturnContent('../app/views/account/settings-change-password-button.php'));
  }
  public function ajaxCorrectPassword() {
    $password = $_POST['password'];
    if(Helpers::isAjax()) {
      // Find the user if it exists
      $result = $this->database->getValue("Account", "", [
        ['email', '=', $_SESSION['email']]
      ]);

      // If there's a matching account...
      if ($result != false) {
        // Get the password and compare
        $loginResult = $this->database->getValue("Login", ["password", "userLevel"], [
          ["userID", "=", $result->userID]
        ]);
        if (password_verify($password, $loginResult->password)) {
          die('ok');
        }
        else die('mismatch');
      }

      // If anything happens assume the user inputted a wrong password
      die('error');
    }
  }

  public function ajaxConfirmChangePassword() {
    $newPassword = $_POST['password'];
    if (Helpers::isAjax()) {
      if ($this->changePassword($newPassword)) {
        die('ok');
      }
      else {
        die('error');
      }
    }
  }
}

?>
