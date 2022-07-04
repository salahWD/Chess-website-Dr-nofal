<?php

class Admin {
  private $id;
  public $name;
  public $username;
  private $password;
  private $email;
  public $image;
  private $token;
  private $login;

  public function check_login($data) {
    $errors = [];

    if (isset($data["login"]) && !empty($data["login"])) {
      $login = $data["login"];
      if (strlen($login) > 4) {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
          $login = filter_var($login, FILTER_SANITIZE_EMAIL);
        }else {
          if (preg_match("/[@$#%^&*()]/", $login)) {
            array_push($errors, "email is invalid!");
          }else {
            $login = filter_var($login, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
          }
        }
      }else {
        array_push($errors, "username or email is invalid!");
      }
      $this->login = $login;
    }else {
      array_push($errors, "you must enter username or email!");
    }

    if (isset($data["password"]) && !empty($data["password"]) && is_string($data["password"])) {
      $pass = $data["password"];
      if (strlen($pass) < 4) {
        array_push($errors, "password is invalid!");
      }else {
        $this->password = sha1($pass);
      }
    }else {
      array_push($errors, "you have to enter password!");
    }

    if (count($errors) > 0) {
      return $errors;
    }else {
      return true;
    }

  }

  public function login($data) {

    $check = $this->check_login($data);

    if ($check === true) {
      $db = DB::get_instance();
      $sql = $db->prepare("SELECT * FROM admins WHERE password = :password AND (username = :login OR email = :login)");
      $sql->execute([":password" => $this->password, ":login" => $this->login]);
      if ($sql->rowCount() > 0) {
        return ["success" => true, "admin" => $sql->fetchObject("Admin")];
      }else {
        return ["success" => false, "errors" => ["user is not exsist!"]];
      }
    }else {
      return ["success" => false, "errors" => $check];
    }

  }

  public static function is_valid_token($token) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT id FROM admins WHERE token = ?");
    $sql->execute([$token]);
    if ($sql->rowCount() > 0) {
      $id = $sql->fetch(PDO::FETCH_ASSOC);
      return ["success" => true, "id" => $id["id"]];
    }else {
      return ["success" => false];
    }
  }

  public static function get_admin_session($selection = null) {
    if (isset($selection) && $selection != null && is_string($selection)) {
      return unserialize(base64_decode($_SESSION["admin"]))->$selection ?? null;
    }else {
      return unserialize(base64_decode($_SESSION["admin"]));
    }
  }

  public static function set_admin_session($admin) {
    return $_SESSION["admin"] = base64_encode(serialize($admin));
  }

  public function create_article($data) {
    include_once MODELS_PATH . "article.php";
    $model = new Article();
    $model->set($data);
    return $model->insert($this->id);
  }

}


?>