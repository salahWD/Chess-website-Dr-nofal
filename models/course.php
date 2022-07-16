<?php

class Course {
  public $id;
  public $title;
  public $description;
  public $price;
  public $rate;
  public $date;
  public $active;
  public $duration;
  public $lectures;
  public $creator_id;
  public $creator_name;
  public $creator_image;
  public $image = "course-dafault.jpg";
  private $image_uploaded = false;

  public static function get_course_title($url_title) {
    $db = DB::get_instance();
    $sql = $db->prepare(
      "SELECT C.*, A.name AS creator_name, A.id AS creator_id, A.image AS creator_image, COUNT(L.id) AS lectures FROM courses C
      INNER JOIN admins A ON A.id = C.creator
      LEFT JOIN lectures L ON L.course = C.id
      WHERE C.url_title = ? AND C.active = 1 AND L.active = 1");
    $result = $sql->execute([$url_title]);
    if ($sql->rowCount() > 0) {
      $course = $sql->fetchObject("Course");
      if (!empty($course->id)) {
        return $course;
      }else {
        return false;
      }
    }else {
      return false;
    }
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
  
  public static function get_lectures($id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM lectures WHERE course = ? AND active = 1 ORDER BY lectures.order");
    $result = $sql->execute([$id]);
    if ($sql->rowCount() > 0) {
      include_once MODELS_PATH . "lecture.php";
      return $sql->fetchAll(PDO::FETCH_CLASS, "Lecture");
    }else {
      return false;
    }
  }
  
  public static function get_price($id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT price FROM courses WHERE id = ?");
    $result = $sql->execute([$id]);
    if ($sql->rowCount() > 0) {
      return $sql->fetch(PDO::FETCH_ASSOC)["price"];
    }else {
      return false;
    }
  }
  
  public static function get_lectures_watched($user, $id) {
    $db = DB::get_instance();
    $sql = $db->prepare(
      "SELECT L.id FROM lectures L
      INNER JOIN lectures_done LD ON LD.lecture = L.id
      WHERE L.course = ? AND L.active = 1 AND LD.user = ?");
    $result = $sql->execute([$id, $user]);
    if ($sql->rowCount() > 0) {
      return array_column($sql->fetchAll(PDO::FETCH_ASSOC), "id");
    }else {
      return [];
    }
  }

  public static function is_enrolled(int $user, int $id) {
    $db = DB::get_instance();

    $sql = $db->prepare("SELECT id FROM lectures_done WHERE user = ? AND course = ? AND lecture IS NULL");
    $result = $sql->execute([$user, $id]);
  
    return $sql->rowCount() > 0;

  }

  public static function free_enroll(int $id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT price FROM courses WHERE id = ?");
    $result = $sql->execute([$id]);

    $price = $sql->fetch(PDO::FETCH_ASSOC)["price"];
    return intval($price) === 0 || empty($price);

  }

  public static function enroll(int $user, int $id) {
    $db = DB::get_instance();

    $sql = $db->prepare("INSERT INTO lectures_done (user, course) VALUES (?, ?)");
    $result = $sql->execute([$user, $id]);
    return $result;

  }

  public static function isset_course_id(int $id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT url_title FROM courses WHERE id = ?");
    $result = $sql->execute([$id]);

    if ($sql->rowCount() > 0) {
      return $sql->fetch(PDO::FETCH_ASSOC)["url_title"];
    }else {
      return false;
    }

  }

  public static function isset_course_title($url_title) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT id FROM courses WHERE url_title = ?");
    $result = $sql->execute([$url_title]);

    if ($sql->rowCount() > 0) {
      return $sql->fetch(PDO::FETCH_ASSOC)["id"];
    }else {
      return false;
    }

  }

  public static function get_session($select) {
    $info = null;
    if (isset($_SESSION["course/$select"])) {
      $info = $_SESSION["course/$select"];
      unset($_SESSION["course/$select"]);
    }
    if ($errors != null && is_array($errors) && count($errors) > 0) {
      return $info;
    }else {
      return null;
    }
  }

  public static function set_session($name, $data) {
    if (!isset($_SESSION)) {
      session_start();
    }
    if (isset($_SESSION["course/$name"])) {
      unset($_SESSION["course/$name"]);
    }
    $_SESSION["course/$name"] = $data;
    return true;
  }

  public function set($data) {
    
    include_once CLASS_PATH . "formvalidate.php";
    $errors = [];

    if (isset($data["course_id"]) && is_numeric(intval($data["course_id"]))) {
      if (intval($data["course_id"]) > 0) {
        $this->id = Form::valid_int($data["course_id"]);
      }else {
        $errors["title"] = "something went wrong. please try again";
      }
    }

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
      if (intval($data["price"]) > -1 && intval($data["price"]) < 1500) {
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

    if (isset($data["description"]) && is_string($data["description"])) {
      if (strlen($data["description"]) > 10) {
        $this->description = Form::valid_str($data["description"]);
      }else {
        $errors["description"] = "description have to be at least 10 chaacters!";
      }
    }else {
      $errors["description"] = "description is invalid!";
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

    $this->active = isset($data["active"]) && $data["active"] == 1 ? 1 : 0;

    $info = [
      "title" => $data["course_title"],
      "price" => $data["price"],
      "description" => $data["description"],
    ];

    if (count($errors) > 0) {
      return ["success" => false, "errors" => $errors, "info_holder" => $info];
    }else {
      return ["success" => true];
    }

  }

  public function insert() {
    $db = DB::get_instance();

    $sql = $db->prepare("SELECT id FROM courses WHERE title = ?");
    $sql->execute([$this->title]);

    if ($sql->rowCount() > 0) {
      return ["success" => false, "errors" => ["title" => "this name is taken"]];
    }else {

      $execute = [
        ":tit"  => $this->title,
        ":des"  => $this->description,
        ":pri"  => $this->price,
      ];

      $image = "";
      if ($this->image_uploaded) {
        $image_nam = ", image";
        $image_val = ", :ima";
        $execute[":ima"] = $this->image["new_name"];
        move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
      }

      $sql = $db->prepare(
        "INSERT INTO courses (title, price, description, date $image_nam)
        VALUES (:tit, :pri, :des, NOW() $image_val)");
      $res = $sql->execute($execute);

      if ($res) {
        return ["success" => true, "id" => $db->lastInsertId()];
      }else {
        return ["success" => false];
      }

    } 

  }

  public function save() {

    $db = DB::get_instance();

    $stmt = $db->prepare("SELECT id FROM courses WHERE title = ? AND id != ?");
    $stmt->execute([$this->title, $this->id]);

    if ($stmt->rowCount() == 0) {

      $execute = [
        ":tit"  => $this->title,
        ":des"  => $this->description,
        ":pri"  => $this->price,
        ":act"  => $this->active,
        ":id"   => $this->id
      ];

      $image = "";
      if ($this->image_uploaded) {
        $image = ", image = :ima";
        $execute[":ima"] = $this->image["new_name"];
        move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
      }

      $sql = $db->prepare(
        "UPDATE courses SET title = :tit, description = :des, price = :pri, active = :act $image WHERE id = :id");
      $res = $sql->execute($execute);

      return ["success" => $res];
    }else {
      return ["success" => false, "error" => ["title" => "this title is used from another course!"]];
    }

  }

  public static function delete($id) {
    
    $db = DB::get_instance();

    $sql = $db->prepare("DELETE FROM courses WHERE id = ?");
    $res = $sql->execute([$id]);
    
    return ["success" => $res];
  }


}

?>