<?php

class Account {
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
    Helpers::makeAlert('account', 'Wrong username and/or password. Please try again.');
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

    if (!isset($_GET['register'])) {
      Helpers::makeAlert('account', 'Logged in. Welcome back '. $result->fName .'!');
    }
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

    Helpers::makeAlert('account', 'Succesfully logged out.');
    $_POST['logout'] = true;
  }

  /**
   * Register the user
   */
  private function register() {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation checks
    // The way it does things is by returning false on the register check and
    // stop the function from executing register commands
    if ($password != $_POST["passwordConfirm"]) {
      Helpers::makeAlert("account", "Sorry, the password doesn't match. Please try again.");
      return $_POST["register"] = false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Helpers::makeAlert("account", "That doesn't look like an email. Please input a valid email.");
      return $_POST["register"] = false;
    }
    $duplicateUser = $this->database->getValue("Account", "", [['email', '=', $email]]);
    if ($duplicateUser) {
      Helpers::makeAlert("account", "It seems you have registered. If you forget your password you can reset it.");
      return $_POST["register"] = false;
    }

    // ---
    // That's the end of validation checks. If it passes then let's register the user
    $account = $this->database->insertValue("Account", [
      ['fName', $fname],
      ['lName', $lname],
      ['email', $email]
    ]);

    // Get the new ID of the account we just created
    $id = $this->database->insert_id;

    if ($account) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $credentials = $this->database->insertValue("Login", [
        ['userID', $id],
        ['password', $hash],
        ['userLevel', 0]
      ]);

      if ($credentials) {
        // Successfully registered. Automatically log the user in
        $_POST['loginEmail'] = $email;
        $_POST['loginPassword'] = $password;
        $this->login();

        Helpers::makeAlert("account", "Successfully registered, welcome ". $fname ."!");
        return $_POST["register"] = true;
      }

      else {
        // Putting the password in the database fails. Revert the account insertion
        $this->database->deleteValue("Account", [["userID", "=", $id]]);
      }
    }

    // This gets called if the return is not made
    Helpers::makeAlert("account", "Something is wrong and we can't register your account right now. Please try again later.");
    return $_POST["register"] = false;
  }
}

?>
