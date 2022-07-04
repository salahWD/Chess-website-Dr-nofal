<?php

class Course {
  public $id;
  public $image;
  public $title;
  public $description;
  public $price;
  public $rate;
  public $date;
  public $duration;

  public function set($data) {
    
    include_once CLASS_PATH . "formvalidate.php";
    $errors = null;

    if (isset($data["course_title"]) && is_string($data["course_title"])) {
      if (strlen($data["course_title"]) > 4) {
        $this->title = Form::valid_str($data["course_title"]);
      }else {
        $errors["title"] = "title can't be less than 5 characters!";
      }
    }else {
      $errors["title"] = "title is invalid!";
    }

    if (isset($data["price"]) && is_numeric(intval($data["price"]))) {
      if (intval($data["price"]) > 0 && intval($data["price"]) < 1500) {
        $this->price = Form::valid_int($data["price"]);
      }else {
        $errors["price"] = "price must be a number between 0 and 1500 !";
      }
    }else {
      $errors["price"] = "price is invalid!";
    }

    if (isset($data["description"]) && is_string($data["description"])) {
      if (strlen($data["description"]) > 10) {
        $this->description = Form::valid_str($data["description"]);
      }else {
        $errors["description"] = "description have to be at least 10 chaacters!";
      }
    }else {
      $errors["description"] = "description is invalid!";
    }

    $info = [
      "title" => $data["course_title"],
      "price" => $data["price"],
      "description" => $data["description"],
    ];

    if (is_array($errors) && count($errors) > 0) {
      return ["success" => false, "errors" => $errors, "info_holder" => $info];
    }else {
      return ["success" => true];
    }

  }

  public function insert() {
    $db = DB::get_instance();
    $sql = $db->prepare(
      "INSERT INTO courses (title, price, description, date)
      VALUES (?, ?, ?, NOW())");
    $sql->execute([
      $this->title,
      $this->price,
      $this->description
    ]);

    if ($sql->rowCount() > 0) {
      return ["success" => true, "id" => $db->lastInsertId()];
    }else {
      return ["success" => false];
    }

  }

  public static function get_active_course(int $id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM courses WHERE courses.id = ? AND courses.active = 1");
    $result = $sql->execute([$id]);
    return $sql->rowCount() > 0 ? $sql->fetchObject("Course"): false;
  }

  public static function get_course(int $id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM courses WHERE courses.id = ?");
    $result = $sql->execute([$id]);
    return $sql->rowCount() > 0 ? $sql->fetchObject("Course"): false;
  }

  public static function get_active_courses() {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM courses WHERE courses.active = 1");
    $result = $sql->execute();
    return $sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_CLASS, "Course"): false;
  }
  
  public static function get_all_courses() {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM courses");
    $result = $sql->execute();
    return $sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_CLASS, "Course"): false;
  }

  public function get_img_url() {
    return IMG_URL . $this->image;
  }

  public static function get_errors_session() {
    $errors = null;
    if (isset($_SESSION["course/errors"])) {
      $errors = $_SESSION["course/errors"];
      unset($_SESSION["course/errors"]);
    }
    if ($errors != null && is_array($errors) && count($errors) > 0) {
      return $errors;
    }else {
      return null;
    }
  }

  public static function set_errors_session($data) {
    if (isset($_SESSION["course/errors"])) {
      unset($_SESSION["course/errors"]);
    }
    $_SESSION["course/errors"] = $data;
    return true;
  }
  
  public static function get_info_holder_session() {
    $info = null;
    if (isset($_SESSION["course/info"])) {
      $info = $_SESSION["course/info"];
      unset($_SESSION["course/info"]);
    }
    if ($info != null && is_array($info) && count($info) > 0) {
      return $info;
    }else {
      return null;
    }
  }

  public static function set_info_holder_session($data) {
    if (isset($_SESSION["course/info"])) {
      unset($_SESSION["course/info"]);
    }
    $_SESSION["course/info"] = $data;
    return true;
  }


}

?>