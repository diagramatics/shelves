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

    else if (isset($_GET["changeAccountSettings"])) {
      $this->changeAccountSettings();
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
      $loginResult = $this->database->getValue("Login", ["password", "userLevel"], [
        ["userID", "=", $result->userID]
      ]);
      if (password_verify($password, $loginResult->password)) {
        // If the password matches then log in
        return $this->setCredentials($result, $loginResult);
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
  private function setCredentials($result, $loginResult) {
    $_SESSION["userID"] = $result->userID;
    $_SESSION["fName"] = $result->fName;
    $_SESSION["email"] = $result->email;
    $_SESSION["userLevel"] = $loginResult->userLevel;

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
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }
    session_destroy();
    $_POST['logout'] = true;
  }

  /**
   * Register the user
   */
  private function register() {
    // TODO: Implement this
    $_POST["register"] = true;
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
      return $_POST['confirmAddAddress'] = $addAddressResult;
    }

    // If it is not some minor interaction with the form then update the whole thing

    // TODO: An update revert function if it fails? Should it be baked on Database.php instead?
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

    // Return false if any of these results are false
    $_POST["changeAccountSettings"] = ($profileUpdateResult && $addressResetResult && $addressUpdateResult);
  }
}

?>
