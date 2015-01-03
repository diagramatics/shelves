<?php

class App {
  // Three defaults: default controller, default method, and default parameters
  protected $controller = 'HomeController';
  protected $method = 'index';
  protected $params = [];

  // Constructor
  public function __construct() {
    // Start session first
    session_start();

    // Debugging
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    // Get the URL of the current directory being run on
    $url = $this->parseUrl();

    // Now try connecting to the database
    $database = new Database();
    // Then set up the user class
    $user = new Account($database);

    // Check if the controller exists first
    if (file_exists('../app/controllers/'. ucfirst($url[0]).'Controller.php')) {
      $this->controller = ucfirst($url[0]).'Controller';
      // If it exists, set it and take it out from the passed data array
      unset($url[0]);
    }

    // Now include the controller and initialize
    require_once '../app/controllers/'. $this->controller .'.php';
    $this->controller = new $this->controller($database);

    // Check if the method inside the controller exists
    if (isset($url[1])) {
      if(method_exists($this->controller, $url[1])) {
        // If it does, then set it and take it out from the passed data array
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    // After the controller and the method is taken out from the array,
    // we assume the rest of the array element is a parameter.
    // So now set everything else in the array as a parameter inside the params array.
    $this->params = $url ? array_values($url) : [];


    call_user_func_array([$this->controller, $this->method], $this->params);

    // After everything necessary to show content is finished,
    // close the database connection.
    $database->close();
  }

  public function parseUrl() {
    if(isset($_GET['url'])) {
      return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
  }
}

?>
