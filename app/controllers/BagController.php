<?php

class BagController extends Controller {

  public function index() {
    $this->view('bag/index', [
      'title' => 'Your Shopping Bag'
      ]);
    }

  }

  ?>
