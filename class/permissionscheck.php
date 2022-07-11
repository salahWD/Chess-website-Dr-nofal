<?php

class PermissionsCheck {

  public static function is_valid_api_request($args, $actions) {
    
    if (isset($args["token"]) && !empty($args["token"]) && is_string($args["token"]) && strlen($args["token"]) == 10) {
      $token = $args["token"];

      if (isset($args["action"]) && in_array($args["action"], $actions)) {
        include_once MODELS_PATH . "admin.php";
        return array_merge(Admin::is_valid_token($token), ["action" => $args["action"]]);// merged([success, id][action])
      }
    }else {
      return ["success" => false];
    }

  }

  public static function is_valid_admin_request() {
    if (isset($_SESSION["admin"]) && !empty($_SESSION["admin"])) {
      return true;
    }else {
      return false;
    }

  }

}

?>