<?php

class Course {
  public $id;
  public $image;
  public $title;
  public $desc;
  public $price;
  public $rate;
  public $date;
  public $duration;

  public function get(int $id) {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM courses WHERE courses.id = ? AND courses.active = 1");
    $result = $sql->execute([$id]);
    return $sql->rowCount() > 0 ? $sql->fetchObject("Course"): false;
  }

  public function get_all() {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM courses WHERE courses.active = 1");
    $result = $sql->execute();
    return $sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_CLASS, "Course"): false;
  }
  
  public function get_img_url() {
    return IMG_URL . $this->image;
  }
}

?>