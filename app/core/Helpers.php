<?php

class Helpers {
  public static function ajaxReturnContent($template, $values = []) {
    if (Helpers::isAjax()) {
      // Extract all the values to separate variables for the template to use
      extract($values);
      // Use output buffering to parse in the alert and evaluate the PHP expressions
      ob_start();
      require_once($template);
      $contents = ob_get_contents();
      ob_end_clean();

      // And return it to function caller
      return $contents;
    }
  }
  public static function makeSlug($string) {
    return trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($string)), '-');
  }

  public static function makeAlert($id, $string) {
    $alertString = $string;
    $alertId = $id;
    include '../app/views/base/alerts/base-alert.php';
  }

  public static function ajaxMakeAlert($id, $string) {
    $alertString = $string;
    $alertId = $id;
    die(Helpers::ajaxReturnContent('../app/views/base/alerts/base-alert.php', array(
      'alertString' => $string,
      'alertId' => $id
    )));
  }

  /**
  * A quick version of checking if the variable exists and output it if it does
  * If not output an empty string
  */
  public static function orEmpty(&$anything1, &$anything2 = "", &$anything3 = "") {
    return !empty($anything1) ? $anything1 : (!empty($anything2) ? $anything2 : (!empty($anything3) ? $anything3 : ""));
  }

  public static function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }
}

?>
