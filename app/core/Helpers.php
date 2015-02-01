<?php

class Helpers {
    public static function makeSlug($string) {
      return trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($string)), '-');
    }

    public static function makeAlert($id, $string) {
      $alertString = $string;
      $alertId = $id;
      include '../app/views/base/alerts/base-alert.php';
    }

    /**
     * A quick version of checking if the variable exists and output it if it does
     * If not output an empty string
     */
    public static function orEmpty(&$anything1, &$anything2 = "", &$anything3 = "") {
      return !empty($anything1) ? $anything1 : (!empty($anything2) ? $anything2 : (!empty($anything3) ? $anything3 : ""));
    }
}

?>
