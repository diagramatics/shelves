<?php

class AccountController extends Controller {
  public function index() {
    // TODO: Implement an account overview page

    $this->view("account/index", [
      "title" => "Your Account Overview"
    ]);
  }

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
      $this->view("account/settings-error", ["title" => "Whoops."]);
    }
  }

  /**
    * Change the account settings
    */
  private function changeAccountSettings() {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $addressID = $_POST['address'];

    // Some toggle buttons to show more information on the form
    // These are just PHP fallbacks. Normally they are handled with JavaScript
    if (isset($_POST['addAddress'])) {
      return;
    }

    // Add a new address
    else if (isset($_POST['confirmAddAddress'])) {
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

    else {
      // If it is not some minor interaction with the form then update the whole thing
      // Stop autocommit so we can rollback it in case of deletion problems
      $this->database->autocommit(false);
      $profileUpdateResult = $this->database->updateValue("Account", [
        ["fName", $fName],
        ["lName", $lName]
      ], [
        ["email", "=", $_SESSION['email']]
      ]);

      $addressResetResult = $this->database->updateValue("Address", [
        ["primaryAddress", "0"]
      ], [
        ["userID", "=", $_SESSION['userID']]
      ]);

      $addressUpdateResult = $this->database->updateValue("Address", [
        ["primaryAddress", "1"]
      ], [
        ["addressID", "=", $addressID]
      ]);

      if ($profileUpdateResult && $addressResetResult && $addressUpdateResult) {
        $this->database->commit();
        Helpers::makeAlert("accountSettings", "You account settings has been updated.");
      }
      else {
        Helpers::makeAlert("accountSettings", "There is something wrong in updating your account settings. Please try again.");
      }
      $this->database->autocommit(true);
    }
  }
}

?>
