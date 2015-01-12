<?php

class Helpers {
    public static function makeSlug($string) {
      return trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($string)), '-');
    }
}

?>
