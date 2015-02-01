<?php

class AjaxController extends Controller {
  public function ajaxMakeAlert() {
    die (Helpers::ajaxMakeAlert($_POST['alertID'], $_POST['alertString']));
  }
}

?>
