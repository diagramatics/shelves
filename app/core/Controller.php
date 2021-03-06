<?php

class Controller {

  protected $database;

  public function __construct($db) {
    $this->database = $db;
  }

  protected function model($model) {
    require_once '../app/models/'. $model .'.php';
    return new $model($this->database);
  }

  protected function view($view, $data = [], $noBase = false) {
    // Some checks on default data, in case there's any forgotten things
    $this->validateViewData($data, 'title');

    // Put in the header stuff first if $noBase is not specified
    if (!$noBase) {
      require_once '../app/views/base/header.php';
    }
    // Now put in the content in the respective view of the section
    // If it's an array or views, do a loop to load them
    if (is_array($view)) {
      for ($i = 0; $i < count($view); $i++) {
        require_once '../app/views/'. $view[$i] .'.php';
      }
    }
    // If it isn't an array, do the usual thing and load it
    else {
      require_once '../app/views/'. $view .'.php';
    }
    // Then put the footer if $noBase is not specified
    if (!$noBase) {
      $data['footerCategories'] = array();
      $categories = $this->database->getValues("Category", "");
      if ($categories) {
        foreach ($categories as $category) {
          $model = $this->model("CategoryModel");
          $model->parse($category, "", "get");
          array_push($data['footerCategories'], $model->extract());
        }
      }
      require_once '../app/views/base/footer.php';
    }
  }

  protected function view404() {
    $this->view('base/404', ['title' => 'Page Not Found']);
  }

  protected function validateViewData(&$data, $key) {
    if (!array_key_exists($key, $data)) {
      $data[$key] = '';
      trigger_error("You haven't set a value for key '".$key."' yet.");
    }
    else return $data[$key];
  }
}

?>
