<?php

class Account {
  // TODO: Implement the user itself.
  // Constructor should have a parameter to get user ID or other credentials
  // and verify it on the constructor to login.
  private $database;
  public function __construct($database) {
    $this->database = $database;
    if (isset($_GET["login"])) {
      $this->login();
    }

    else if (isset($_GET["logout"])) {
      $this->logout();
    }

    else if (isset($_GET["register"])) {
      $this->register();
    }
  }

  public function isLoggedIn() {
    // TODO: Implement this by checking the session/cookie if there's any user credentials stored from logging in function
    return isset($_SESSION["email"]);
  }

  private function login() {
    // Get the login email and password
    $email = $_POST["loginEmail"];
    $password = $_POST["loginPassword"];

    // Now try to find the user if it exists
    $result = $this->database->getValue("Account", "", [
      ['email', '=', $email]
    ]);

    // If there's a matching account...
    if ($result != false) {
      // Get the password and compare
      $loginResult = $this->database->getValue("Login", ["password"], [
        ["userID", "=", $result->userID]
      ]);
      if (password_verify($password, $loginResult->password)) {
        // If the password matches then log in
        return $this->setCredentials($result);
      }
      // If it doesn't match the return below will be called
    }

    // TODO: Error view if password or email (or both) doesn't match
    return $_POST['login'] = false;
  }

  /**
   * Sets the credentials for the user on the PHP session
   *
   * @param     $result   The data row on the MySQL
   * @return    void
   */
  private function setCredentials($result) {
    $_SESSION["fName"] = $result->fName;
    $_SESSION["email"] = $result->email;
    $_SESSION["userLevel"] = $result->userLevel;

    $_POST['login'] = true;
  }

  /**
   * Logout the user by wiping the PHP session data.
   *
   * @param
   * @return    void
   */
  private function logout() {
    // On logging out remove the credentials
    $_SESSION["fName"] = null;
    $_SESSION["email"] = null;

    $_POST['logout'] = true;

    // TODO: Clear memory, destroy session (Alex FTW)
  }

  /**
   * Register the user
   */
  private function register() {
    $_POST["register"] = true;
  }
}

?>
