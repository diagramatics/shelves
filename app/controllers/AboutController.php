<?php

class AboutController extends Controller {
  public function index() {
    $this->view('about/index', array(
      'title' => 'About Us'
    ));
  }
}

?>
