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
  public static function valid_image($image) {

    $mime 			= ["image/jpeg", "image/png", "image/webp"];
    $exts_name 	= ["jpeg", "jpg", "png", "webp"];
    $ext = "";
    $error 			= [];

    if (isset($image["type"]) && is_string($image["type"])) {
      if (!in_array($image["type"], $mime)) {
        $error[] = "file type is not allowed, only (" . implode(", ", $exts_name) . ") is allowed";
      }
    }

    if (isset($image["name"]) && is_string($image["name"])) {
      $name_arr 	=  explode(".", $image["name"]);
      $ext 				= end($name_arr);
      if (!in_array($ext, $exts_name)) {
        $error[] = "file type is not allowed, only (" . implode(", ", $exts_name) . ") is allowed";
      }
    }else {
      $error[] = "file name is not exist!";
    }

    if (isset($image["size"]) && !empty($image["size"]) && is_numeric(intval($image["size"]))) {
      if (!(((intval($image["size"]) / 1024) / 1024) < 3)) {// size < 3MB
        $error[] = "file size is too large (max = 3MB)";
      }
    }else {
      $error[] = "file size is not exist!";
    }

    if (count($error) > 0) {
      return ["success" => false, "errors" => $error];
    }else {
      include_once CLASS_PATH . "image.php";
      $img_name = Image::generate_name(10) . "." . $ext;
      return ["success" => true, "info" => ["new_name" => $img_name, "tmp_name" => $image["tmp_name"]]];
    }

  }

}

?>