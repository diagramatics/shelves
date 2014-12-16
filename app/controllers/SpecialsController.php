<?php

class SpecialsController extends Controller {

  public function index() {
    $this->view('specials/index', [
      'title' => 'Specials'
      ]);
    }

}

?>
