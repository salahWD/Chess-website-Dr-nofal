<?php

class Admin {
  private $id;
  public  $name;
  public  $username;
  private $password;
  private $email;
  public  $image;
  private $token;
  private $login;
  private $image_uploaded = false;
  private $password_updated = false;

  public function check_login($data) {
    $errors = [];

    if (isset($data["login"]) && !empty($data["login"])) {
      $login = $data["login"];
      if (strlen($login) > 4) {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
          $login = filter_var($login, FILTER_SANITIZE_EMAIL);
        }else {
          if (preg_match("/[@$#%^&*()]/", $login)) {
            array_push($errors, "email or username is invalid!");
          }else {
            $login = filter_var($login, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
          }
        }
        $this->login = $login;
      }else {
        array_push($errors, "username or email too short less than 5 chars");
      }
    }else {
      array_push($errors, "you must enter username or email!");
    }
    $this->password = sha1($data["password"]);

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
        return ["success" => false, "errors" => ["username or password is wrong!"]];
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

  public static function isset_admin_session() {
    if (isset($_SESSION["admin"]) && !empty($_SESSION["admin"])) {
      return true;
    }else {
      return false;
    }
  }

  public static function set_errors($errors) {

    if (!isset($_SESSION)) {
      session_start();
    }
    return $_SESSION["admin/errors"] = $errors;
  }

  public static function get_errors() {
    if (!isset($_SESSION)) {
      session_start();
    }
    if (isset($_SESSION["admin/errors"])) {
      return $_SESSION["admin/errors"];
    }else {
      return null;
    }
  }

  public static function get_admin_session($selection = null) {
    if (isset($selection) && $selection != null && is_string($selection)) {
      return unserialize(base64_decode($_SESSION["admin"]))->$selection ?? null;
    }else {
      return unserialize(base64_decode($_SESSION["admin"]));
    }
  }

  public static function get_admin_db($id) {

    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM admins WHERE id = ?");
    $sql->execute([$id]);

    if ($sql->rowCount() > 0) {
      return $sql->fetchObject("Admin");
    }else {
      header("Location: " . Router::route("logout"));
    }
  }

  public static function set_admin_session($admin) {
    return $_SESSION["admin"] = base64_encode(serialize($admin));
  }

  public function set_data($data) {
    include_once CLASS_PATH . "formvalidate.php";
    $errors = [];

    if (isset($data["name"]) && is_string($data["name"])) {
      if (strlen($data["name"]) > 4 && strlen($data["name"]) < 30) {
        if(preg_match("/[a-z]{2,}/i", $data["name"])) {
          $this->name = Form::valid_str($data["name"]);
        }else {
          $errors["name"] = "name have to contain at least 2 alphabet characters";
        }
      }else {
        $errors["name"] = "name can't be less than 5 or larger than 30 characters";
      }
    }else {
      $errors["name"] = "name is required";
    }
    
    if (isset($data["username"]) && is_string($data["username"])) {
      if (strlen($data["username"]) > 4 && strlen($data["username"]) < 30) {
        if(preg_match("/[a-z]{2,}/i", $data["username"])) {
          $this->username = Form::valid_str($data["username"]);
        }else {
          $errors["username"] = "username have to contain at least 2 alphabet characters";
        }
      }else {
        $errors["username"] = "username can't be less than 5 or larger than 30 characters";
      }
    }else {
      $errors["username"] = "username is required";
    }

    if (isset($data["email"]) && is_string($data["email"])) {
      if(filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        $this->email = $data["email"];
      }else {
        $errors["email"] = "email is invalid!";
      }
    }else {
      $errors["email"] = "email is required";
    }

    if (isset($data["password"]) && is_string($data["password"])) {
      if (strlen($data["password"]) > 4) {
        $this->password = sha1($data["password"]);
        $this->password_updated = true;
      }else {
        $errros["password"] = "password cant be less than 5 characters";
      }
    }

    if (isset($data["image"]) && !empty($data["image"])) {
      $image = Form::valid_image($data["image"]);

      if ($image["success"]) {
        $this->image = $image["info"];// data = ["new_name", "tmp_name"]
        $this->image_uploaded = true;
      }else {
        $errors["image"] = $image["errors"];// error code 3
      }
    }

    $info = [
      "name" 			=> $data["name"],
      "username" 	=> $data["username"],
      "email"     => $data["email"],
      "password"  => $data["password"]
    ];

    if (count($errors) > 0) {
      return ["success" => false, "errors" => $errors, "info_holder" => $info];
    }else {
      return ["success" => true];
    }

  }

  public function save() {
    // return [boolean: success, arr: error]

    $db = DB::get_instance();
    $error = null;

    $unique_check = $db->prepare("SELECT id FROM admins WHERE name = ? AND id != ?");
    $unique_check->execute([$this->name, $this->id]);
    if ($unique_check->rowCount() > 0) {
      $error["name"] = "this name is used!";
    }
    
    $unique_check = $db->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
    $unique_check->execute([$this->username, $this->id]);
    if ($unique_check->rowCount() > 0) {
      $error["username"] = "this username is used!";
    }

    if ($error == null) {
    
      $execute = [
        ":name" => $this->name,
        ":username" => $this->username,
        ":email" => $this->email,
        ":id" => $this->id
      ];
      $password_field = "";
      $image_field = "";

      if ($this->password_updated) {
        $password_field  = ", password = :password";
        $execute[":password"] = $this->password;
      }

      if ($this->image_uploaded) {
        $image_field  = ", image = :image";
        $execute[":image"] = $this->image["new_name"];
        move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
        $this->image = $this->image["new_name"];
      }

      $stmt = $db->prepare("UPDATE admins SET name = :name, username = :username, email = :email $password_field $image_field
      WHERE id = :id");
      $result = $stmt->execute($execute);

      return ["success" => $result];

    }else {
      return ["success" => false, "errors" => $error];// duplicate entry [error => 1]
    }

  }

  public function get_email() {
    return $this->email;
  }

  public function get_id() {
    return $this->id;
  }

  public function set_id($id) {
    if (!empty($id) && is_numeric(intval($id)) && intval($id) > 0) {
      $this->id = intval($id);
      return true;
    }else {
      return true;
    }
  }

  public function create_article($data) {
    include_once MODELS_PATH . "article.php";
    $model = new Article();
    $model->set($data);
    return $model->insert($this->id);
  }

}


?>