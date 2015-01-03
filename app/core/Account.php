<?php

class Account {
  // TODO: Implement the user itself.
  // Constructor should have a parameter to get user ID or other credentials
  // and verify it on the constructor to login.
  private $database;
  public function __construct($database) {
    $this->database = $database;
    if (isset($_POST["login"])) {
      $this->login();
    }

    else if (isset($_POST["logout"])) {

    }
  }

  public function isLoggedIn() {
    // TODO: Implement this by checking the session/cookie if there's any user credentials stored from logging in function
    return isset($_SESSION["email"]);
  }

  private function login() {
    $email = $_POST["loginEmail"];
    $password = $_POST["loginPassword"];

    $result = $this->database->getValue("Account", "", [
      ['email', '=', $email]
    ]);

    if ($result !== false) {
      // Get the password and compare
      $loginResult = $this->database->getValue("Login", ["password"], [
        ["userID", "=", $result->userID]
      ]);
      if (password_verify($password, $loginResult->password)) {
        return $this->setCredentials($result);
      }
      // If it doesn't match the return below will be called
    }

    // TODO: Error view if password or email (or both) doesn't match
    return false;
  }

  private function setCredentials($result) {
    $_SESSION["fName"] = $result->fName;
    $_SESSION["email"] = $result->email;
  }
}

?>
