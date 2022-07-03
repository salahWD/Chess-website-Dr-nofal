<?php

class Form {

  public static function valid_str($str) {
    $str = trim($str);
    return filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
  }
  public static function valid_url_title($title) {
    $title = trim($title);
    $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
    $title = preg_replace('/[\s]+/', '-', $title);
    $title = preg_replace('/[-]+/', '-', $title);
    return $title;
  }
  public static function valid_int($int) {
    $int = intval($int);
    return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
  }
  public static function valid_float($float) {
    return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT, [
      FILTER_FLAG_ALLOW_FRACTION, FILTER_FLAG_ALLOW_THOUSAND
    ]);
  }

}

?>