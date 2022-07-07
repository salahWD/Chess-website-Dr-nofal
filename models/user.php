<?php

  class user {
    public $id;
    public $name;
    public $username;
    public $email;
    public $login;
    public $password;
    public $image = "unknown.jpg";
    public $date;
    private $image_uploaded = false;

    public static function get_recent_users($count) {

      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT U.id, U.name, U.date, U.image FROM users U
        ORDER BY U.`date` LIMIT $count");
      $sql->execute();

      if ($sql->rowCount() > 0) {
        return $sql->fetchAll(PDO::FETCH_CLASS, "user");
      }else {
        return false;
      }

    }// get a $count of last signup users

    public static function get_users() {

      $db = DB::get_instance();
      $sql = $db->prepare("SELECT U.id, U.name, U.email, U.date, U.image FROM users U ORDER BY U.`date`");
      $sql->execute();

      if ($sql->rowCount() > 0) {
        return $sql->fetchAll(PDO::FETCH_CLASS, "user");
      }else {
        return false;
      }

    }// gets all users for manage [ordered by date]

    public static function get_user($id) {

      $db = DB::get_instance();
      $sql = $db->prepare("SELECT * FROM users WHERE id = ?");
      $sql->execute([$id]);

      if ($sql->rowCount() > 0) {
        return $sql->fetchObject("user");
      }else {
        return false;
      }

    }// gets specific user by id

    public function set_login($data) {
      include_once CLASS_PATH . "formvalidate.php";
      $errors = [];

      if (isset($data["login"]) && is_string($data["login"]) && strlen($data["login"]) > 0) {
        if(filter_var($data["login"], FILTER_VALIDATE_EMAIL)) {
          $this->login = filter_var($data["login"], FILTER_SANITIZE_EMAIL);
        }else {
          if (preg_match("/[!@#$%^&*)(><:\"';|]/i" , $data["login"])) {
            $errors["login"] = "username cannot contain special character";
          }else {
            $this->login = $data["login"];
          }
        }
      }else {
        $errors["login"] = "email or username are required";
      }

      if (isset($data["password"]) && is_string($data["password"]) && strlen($data["password"]) > 0) {
        $this->password = sha1($data["password"]);
      }else {
        $errors["password"] = "password is required!";
      }

      $info = ["login" => $data["login"]];

      if (count($errors) > 0) {
        return ["success" => false, "login_errors" => $errors, "login_holder" => $info];
      }else {
        return ["success" => true];
      }

    }// set for login reqeust

    public function set_signup_stage_one($data) {
      include_once CLASS_PATH . "formvalidate.php";
      $errors = [];

      if (isset($data["username"]) && is_string($data["username"])) {
        if (preg_match("/[!@#$%^&*)(><:\"';|]/i" , $data["username"])) {
          $errors["username"] = "username cannot contain special character";
        }
        if (5 > strlen($data["username"]) || 30 < strlen($data["username"])) {
          $errors["username"] = "username have to be between 5 and 30 character";
        }
        if (empty($errors["username"])) {
          $this->username = FORM::valid_str($data["username"]);
        }
      }else {
        $errors["username"] = "username is required";
      }

      if (isset($data["email"]) && is_string($data["email"])) {
        if (filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
          $this->email = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
        }else {
          $errors["email"] = "username cannot contain special character";
        }
      }else {
        $errors["email"] = "email is required";
      }

      if (isset($data["password"]) && is_string($data["password"])) {
        if (strlen($data["password"]) > 4 && strlen($data["password"]) < 31) {
          $this->password = sha1($data["password"]);
        }else {
          $errors["password"] = "password have to be between 5 and 30 characters";
        }
      }else {
        $errors["password"] = "password is required!";
      }

      $info = [
        "username" => $data["username"],
        "email"   => $data["email"],
      ];

      if (count($errors) > 0) {
        return ["success" => false, "errors" => $errors, "signup_holder" => $info];
      }else {
        return ["success" => true];
      }

    }// set for signup stage one request

    public function set_signup_stage_two($data) {
      include_once CLASS_PATH . "formvalidate.php";
      $errors = [];

      if (isset($data["name"]) && is_string($data["name"])) {
        if (preg_match("/[!@#$%^&*)(><:\"';|]/i" , $data["name"])) {
          $errors["name"] = "name cannot contain special character";
        }
        if (5 > strlen($data["name"]) || 30 < strlen($data["name"])) {
          $errors["name"] = "name have to be between 5 and 30 character";
        }
        if (empty($errors["name"])) {
          $this->name = FORM::valid_str($data["name"]);
        }
      }

      if (isset($data["image"]) && !empty($data["image"])) {
        $image = Form::valid_image($data["image"]);
        if ($image["success"]) {
          $this->image = $image["info"];// data = ["new_name", "tmp_name"]
          $this->image_uploaded = true;
        }else {
          $errors["image"] = $image["errors"];
        }
      } 

      $this->image_uploaded = true;
      
      $info = ["name" => $data["name"]];

      if (count($errors) > 0) {
        return ["success" => false, "errors" => $errors, "signup_holder" => $info];
      }else {
        return ["success" => true];
      }

    }// set for signup stage tow request

    public function set_update($data) {
      include_once CLASS_PATH . "formvalidate.php";
      $errors = [];

      if (isset($data["user_id"]) && is_numeric(intval($data["user_id"])) && intval($data["user_id"]) > 0) {
        $this->id = Form::valid_int($data["user_id"]);
      }else {
        $errors["name"] = "something went wrong, please try again";
      }
      
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

      if (isset($data["type"]) && is_string($data["type"])) {
        if (strlen($data["type"]) > 2) {
          $this->tag = Form::valid_str($data["type"]);
        }else {
          $errors["type"] = "type can't be less than 3 character";
        }
      }

      if (isset($data["password"]) && is_string($data["password"])) {
        if (strlen($data["password"]) > 4) {
          $this->password = sha1($data["password"]);
        }else {
          $errros["password"] = "password cant be less than 5 characters";
        }
      }else {
        $errros["password"] = "password is required";
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
				"email"     => $data["email"]
			];

      if (count($errors) > 0) {
        return ["success" => false, "errors" => $errors, "info_holder" => $info];
      }else {
        return ["success" => true];
      }

    }// set for update request

    public function save() {
      // return [boolean: success, arr: error]

      $db = DB::get_instance();
      $error = null;

      $unique_check = $db->prepare("SELECT id FROM users WHERE name = ? AND id != ?");
      $unique_check->execute([$this->name, $this->id]);
      if ($unique_check->rowCount() > 0) {
        $error["name"] = "this name is used!";
      }
      
      $unique_check = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
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
        $image_field = "";

        if ($this->image_uploaded) {
          $image_field  = ", image = :image";
          $execute[":image"] = $this->image["new_name"];
          move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
        }

        $stmt = $db->prepare("UPDATE users SET name = :name, username = :username, email = :email $image_field
        WHERE id = :id");
        $result = $stmt->execute($execute);
        
        return ["success" => $result];

      }else {
        return ["success" => false, "errors" => $error];// duplicate entry [error => 1]
      }

    }// updates DB with $this data

    public static function delete($id) {
      $db = DB::get_instance();
  
      $sql = $db->prepare("DELETE FROM users WHERE id = ?");
      $res = $sql->execute([$id]);
      return ["success" => $res];
    }// deletes specific user by id

    public function login($data) {
      $result = $this->set_login($data);
      if ($result["success"]) {

        $db = DB::get_instance();
        $sql = $db->prepare("SELECT * FROM users WHERE password = :pass AND (username = :login OR email = :login)");
        $sql->execute([":pass" => $this->password, ":login" => $this->login]);

        if ($sql->rowCount() > 0) {
          return ["success" => true, "user" => $sql->fetchObject("User")];
        }else {
          $errors["login"] = "(username or email) or password is wrong";
          return ["success" => false, "login_errors" => $errors, "login_holder" => ["login" => $this->login]];
        }

      }else {
        return $result;
      }
    }// login user request

    public function signup_stage_one($data) {
      $res = $this->set_signup_stage_one($data);
      if ($res["success"]) {

        $db = DB::get_instance();
        $sql = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $res = $sql->execute([$this->username, $this->email, $this->password]);

        if ($res) {
          $_SESSION["signup/stage"] = $db->lastInsertId();
          return ["success" => true];
        }else {
          return ["success" => false, "errors" => ["username" => "somthing went wrong please ty again"]];
        }

      }else {
        return $res;
      }
    }// sign user's initial data

    public function signup_stage_two($data) {
      $res = $this->set_signup_stage_two($data);
      if ($res["success"]) {
        
        $id = self::get_stage_session();
        $db = DB::get_instance();
        $execute = [
          ":name" => $this->name,
          ":id" => $id,
        ];
        
        $image = "";
        if (isset($this->image_uploaded)) {
          $image = ", image = :image";
          $execute[":image"] = $this->image["new_name"];
          move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
        }

        $sql = $db->prepare("UPDATE users SET name = ? $image WHERE id = ?");
        $res = $sql->execute($execute);

        if ($res) {
          self::delete_stage_session();
          return ["success" => true];
        }else {
          return ["success" => false];
        }

      }else {
        return $res;
      }
    }// sign user optinal data

    public static function get_login_errors_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/login/errors"])) {
        $errors = $_SESSION["user/login/errors"];
        unset($_SESSION["user/login/errors"]);
        return $errors;
      }else {
        return null;
      }
    }

    public static function set_login_errors_session($errors) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/login/errors"])) {
        unset($_SESSION["user/erros"]);
      }
      return $_SESSION["user/login/errors"] = $errors;
    }

    public static function get_login_holder_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/login/info"])) {
        $errors = $_SESSION["user/login/info"];
        unset($_SESSION["user/login/info"]);
        return $errors;
      }else {
        return null;
      }
    }

    public static function set_login_holder_session($info) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/login/info"])) {
        unset($_SESSION["user/login/info"]);
      }
      return $_SESSION["user/login/info"] = $info;
    }

    public static function get_signup_errors_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/signup/errors"])) {
        $errors = $_SESSION["user/signup/errors"];
        unset($_SESSION["user/signup/errors"]);
        return $errors;
      }else {
        return null;
      }
    }

    public static function set_signup_errors_session($errors) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/signup/errors"])) {
        unset($_SESSION["user/signup/errors"]);
      }
      return $_SESSION["user/signup/errors"] = $errors;
    }

    public static function get_signup_holder_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/signup/info"])) {
        $info = $_SESSION["user/signup/info"];
        unset($_SESSION["user/signup/info"]);
        return $info;
      }else {
        return null;
      }
    }

    public static function set_signup_holder_session($info) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user/signup/info"])) {
        unset($_SESSION["user/signup/info"]);
      }
      return $_SESSION["user/signup/info"] = $info;
    }

    public static function set_user_session($user) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["user"])) {
        unset($_SESSION["user"]);
      }
      return $_SESSION["user"] = $user;
    }// loads user object to session
    
    public static function get_stage_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      $stage = $_SESSION["signup/stage"];
      if (isset($_SESSION["signup/stage"])) {
        unset($_SESSION["signup/stage"]);
      }
      return $stage;
    }

    public static function delete_stage_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["signup/stage"])) {
        unset($_SESSION["signup/stage"]);
      }
      return true;
    }

    public static function isset_stage_session() {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION["signup/stage"])) {
        return true;
      }else {
        return false;
      }
    }

  }

?>