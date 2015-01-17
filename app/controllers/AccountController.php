<?php

class AccountController extends Controller {
  public function index() {
    // TODO: Implement an account overview page
  }

  public function settings() {
    $model = $this->model("AccountModel");

    $account = $this->database->getValue("Account", "", [
      ["email", "=", $_SESSION["email"]]
    ]);

    $model->setID($account->userID);
    $model->setEmail($account->email);
    $model->setFName($account->fName);
    $model->setLName($account->lName);
    $model->setDob($account->dob);

    $addresses = $this->database->getValues("Address", "", [
      ["userID", "=", $model->getID()]
    ]);

    $this->view("account/settings", [
      "title" => "Account Settings",
      "email" => $model->getEmail(),
      "fName" => $model->getFName(),
      "lName" => $model->getLName(),
      "dob" => $model->getDob(),
      "addresses" => $addresses
    ]);
  }
}

?>
