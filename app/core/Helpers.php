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
}

?>
