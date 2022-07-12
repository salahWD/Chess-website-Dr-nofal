<?php

  class Lecture {

    public $id;
    public $title;
    public $utl_title;
    public $description;
    public $date;
    public $image = "lecture-default.jpg";
    public $course;
    public $active = 0;
    public $order;


    public static function get_lecture_title($url_title) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT lectures.* FROM lectures
        WHERE lectures.url_title = ?");
      $sql->execute([$url_title]);
      if ($sql->rowCount() > 0) {
        return $sql->fetchObject("Lecture");
      }else {
        return null;
      }

    }

  }

?>