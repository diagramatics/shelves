<?php

class AboutController extends Controller {
  public function index() {
    $this->view('about/index', array(
      'title' => 'About Us'
    ));
  }

  public function tos() {
    $this->view('about/tos', array(
      'title' => 'Terms of Services'
    ));

  }

  public function privacy() {
    $this->view('about/privacy', array(
      'title' => 'Privacy Policy'
    ));
  }
}

?>
