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
}

?>
