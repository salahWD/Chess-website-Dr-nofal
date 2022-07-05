<?php

  class Comment {
    public $id;
    public $comment;
    public $replay_on;
    public $article;
    public $article_title;
    public $user;
    public $user_name;
    public $user_image;

    public static function get_recent_comments($count) {

      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT comments.*, articles.title AS article_title, users.name AS user_name, users.image AS user_image
        FROM comments INNER JOIN articles ON articles.id = comments.article
        INNER JOIN users ON users.id = comments.user
        WHERE ISNULL(comments.replay_on) ORDER BY `date` LIMIT $count");
      $sql->execute();

      if ($sql->rowCount() > 0) {
        return $sql->fetchAll(PDO::FETCH_CLASS, "Comment");
      }else {
        return false;
      }

    }


  }

?>