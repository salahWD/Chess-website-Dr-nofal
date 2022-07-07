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
          
          if (!preg_match("/[A-z]{2,}/i" , $data["login"])) {
            $errors["login"] = "username have to containe at least 2 alphabets";
          }
          if (5 > strlen($data["login"]) || 30 < strlen($data["login"])) {
            $errors["login"] = "username have to be between 5 and 30 character";
          }
          if (empty($errors["login"])) {
            $this->login = FORM::valid_str($data["login"]);
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
        if (!preg_match("/[A-z]{2,}/i" , $data["username"])) {
          $errors["username"] = "username have to containe at least 2 alphabets";
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
          $errors["email"] = "email is not valid";
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

      return ["success" => count($errors) > 0 ? false: true, "errors" => $errors ?? [], "signup_holder" => $info];

    }// set for signup stage one request

    public function set_signup_stage_two($data) {
      include_once CLASS_PATH . "formvalidate.php";
      $errors = [];

      if (isset($data["name"]) && is_string($data["name"])) {
        if (5 > strlen($data["name"]) || strlen($data["name"]) > 29) {
          $errors["name"] = "name can't be less than 5 or larger than 30 characters";
        }
        if (!preg_match("/[A-z]{4,}/i", $data["name"])) {
          $errors["name"] = "name have to contain at least 4 alphabet characters";
        }
        if (preg_match("/[!@#$%^&*)(><:\"';|]/i" , $data["name"])) {
          $errors["name"] = "name cannot contain special character";
        }
        if (empty($errors["name"])) {
          $this->name = Form::valid_str($data["name"]);
        }
      }else {
        $errors["name"] = "name is required";
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
      
      $info = ["name" => $data["name"] ?? ""];

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
        if (5 > strlen($data["name"]) || strlen($data["name"]) > 29) {
          $errors["name"] = "name can't be less than 5 or larger than 30 characters";
        }
        if (!preg_match("/[A-z]{4,}/i", $data["name"])) {
          $errors["name"] = "name have to contain at least 4 alphabet characters";
        }
        if (preg_match("/[!@#$%^&*)(><:\"';|]/i" , $data["name"])) {
          $errors["name"] = "name cannot contain special character";
        }
        if (empty($errors["name"])) {
          $this->name = Form::valid_str($data["name"]);
        }
      }else {
        $errors["name"] = "name is required";
      }
      
      if (isset($data["username"]) && is_string($data["username"])) {
        if (!preg_match("/[A-z]{2,}/i" , $data["username"])) {
          $errors["username"] = "username have to containe at least 2 alphabets";
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
        $errors = [];

        if (!self::is_unique_value("username", $this->username)) {
          $errors["username"] = "this username is used from another user";
        }

        if (!self::is_unique_value("email", $this->email)) {
          $errors["email"] = "this email has signed up already";
        }

        if (count($errors) == 0) {

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
          return ["success" => false, "errors" => $errors, "signup_holder" => $res["signup_holder"]];
        }

      }else {
        return $res;
      }
    }// sign user's initial data

    public function signup_stage_two($data) {
      $res = $this->set_signup_stage_two($data);
      if ($res["success"]) {
        
        $id = Router::get_session("signup/stage", false);
        $db = DB::get_instance();
        $execute = [
          ":name" => $this->name,
          ":id" => $id,
        ];
        
        $image = "";
        if ($this->image_uploaded) {
          $image = ", image = :image";
          $execute[":image"] = $this->image["new_name"];
          move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
        }
        $sql = $db->prepare("UPDATE users SET name = :name $image WHERE id = :id");
        $res = $sql->execute($execute);

        if ($res) {
          self::delete_session("signup/stage");
          return ["success" => true, "user" => User::get_user($id)];
        }else {
          return ["success" => false];
        }

      }else {
        return $res;
      }
    }// sign user optinal data

    public static function set_session($name, $value) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
      }
      return $_SESSION[$name] = $value;
    }
    
    public static function delete_session($name) {
      if (!isset($_SESSION)) {
        session_start();
      }
      if (isset($_SESSION[$name])) {
        unset($_SESSION[$name]);
      }
      return true;
    }

    public static function is_unique_value($field, $value) {
      $db = DB::get_instance();
      $sql = $db->prepare("SELECT id FROM users WHERE " . $field . " = ?");
      $sql->execute([$value]);

      if ($sql->rowCount() > 0) {
        return false;
      }else {
        return true;
      }

    }

  }

?>