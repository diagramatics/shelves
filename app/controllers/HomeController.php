<?php

class HomeController extends Controller {
  public function index() {

    $model = $this->model('HomeModel');
    $model->something = "abc";

    $this->view('home/index', [
      'title' => 'Home'
    ]);
  }
}

?>
