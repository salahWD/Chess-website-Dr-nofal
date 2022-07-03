<?php

class Image {
  public static function generate_name($length) {
    $name = "";
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789_";
    for ($i=0; $i < $length; $i++) {
      $name .= $str[random_int(0, strlen($str)-1)];
    }
    return $name;
  }
}
?>