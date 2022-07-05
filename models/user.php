<?php

  class user {
    public $id;
    public $username;
    public $name;
    public $email;
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

    }

    public static function get_users() {

      $db = DB::get_instance();
      $sql = $db->prepare("SELECT U.id, U.name, U.email, U.date, U.image FROM users U ORDER BY U.`date`");
      $sql->execute();

      if ($sql->rowCount() > 0) {
        return $sql->fetchAll(PDO::FETCH_CLASS, "user");
      }else {
        return false;
      }

    }

    public static function get_user($id) {

      $db = DB::get_instance();
      $sql = $db->prepare("SELECT * FROM users WHERE id = ?");
      $sql->execute([$id]);

      if ($sql->rowCount() > 0) {
        return $sql->fetchObject("user");
      }else {
        return false;
      }

    }

    public function set($data) {
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

    }

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

    }

    public static function delete($id) {
      $db = DB::get_instance();
  
      $sql = $db->prepare("DELETE FROM users WHERE id = ?");
      $res = $sql->execute([$id]);
      return ["success" => $res];
    }


  }

?>