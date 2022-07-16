<?php

  class Lecture {

    public $id;
    public $title;
    public $utl_title;
    public $video;
    public $description;
    public $date;
    public $image = "lecture-default.jpg";
    public $course;
    public $active = 0;
    public $order;


    public static function get_lecture_title($url_title) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT L.*, C.title AS course_title FROM lectures L
        INNER JOIN courses C ON C.id = L.course
        WHERE L.url_title = ?");
      $sql->execute([$url_title]);
      if ($sql->rowCount() > 0) {
        return $sql->fetchObject("Lecture");
      }else {
        return null;
      }

    }

    public static function get_progress_info($lecture_id) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT L2.id, L2.title, L2.url_title, L2.order FROM lectures L
        LEFT JOIN lectures L2 ON L2.order >= L.order - 2 AND L2.order < L.order + 3
        WHERE L.id = ? ORDER BY L2.order LIMIT 5");
      $sql->execute([$lecture_id]);
      if ($sql->rowCount() > 0) {

        $next = 1;
        $prev = 0;
        $lectures = $sql->fetchAll(PDO::FETCH_CLASS, "Lecture");
        
        if (count($lectures) > 3) {
          $next = 2;
          $prev = 0;
          $current_l = 2;
          foreach($lectures as $i => $lec) {
            if ($lec->id == $lecture_id) {
              $current_l = $i;
            }
          }
          $lectures = array_slice($lectures, $current_l - 1, 3);
        }else {
          $current_order = array_search($lecture_id, array_column($lectures, "id"));
          $prev = $current_order-1 ?? null;
          $next = $current_order+1 ?? null;
        }

        return ["lectures" => $lectures, "next" => $lectures[$next]->url_title ?? null, "prev" => $lectures[$prev]->url_title ?? null];
          
      }else {
        return null;
      }

    }

    public static function get_watched_lectures(int $user, int $course_id) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT lecture FROM lectures_done WHERE user = ? AND course = ?");
      $sql->execute([$user, $course_id]);
      if ($sql->rowCount() > 0) {
        return array_column($sql->fetchAll(PDO::FETCH_ASSOC), "lecture");
      }else {
        return [];
      }

    }

    public static function isset_lecture_title($url_title) {
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT C.id, C.url_title FROM lectures L
        INNER JOIN courses C ON C.id = L.course
        WHERE L.url_title = ?");
      $result = $sql->execute([$url_title]);
  
      if ($sql->rowCount() > 0) {
        return $sql->fetch(PDO::FETCH_ASSOC);
      }else {
        return false;
      }
    }

  }

?>