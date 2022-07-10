<?php

  class Comment {
    public $id;
    public $comment;
    public $reply_on;
    public $article;
    public $article_title;
    public $user;
    public $user_name;
    public $user_image;
    public $replies_count = 0;

    public static function get_recent_comments($count) {

      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT comments.*, articles.title AS article_title, users.name AS user_name, users.image AS user_image
        FROM comments INNER JOIN articles ON articles.id = comments.article
        INNER JOIN users ON users.id = comments.user
        WHERE ISNULL(comments.reply_on) ORDER BY `date` LIMIT $count");
      $sql->execute();

      if ($sql->rowCount() > 0) {
        return $sql->fetchAll(PDO::FETCH_CLASS, "Comment");
      }else {
        return false;
      }

    }

    public static function valid_comment($comment) {

      $comment = trim($comment);
      return filter_var($comment, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

    }

    private static function valid_id($id) {
      return abs(intval(filter_var($id, FILTER_SANITIZE_NUMBER_INT)));
    }

    public static function add_comment($user, $comment, $article_id, $replied_id = null) {

      $id = self::valid_id($article_id);

      if (is_numeric($id)) {

        $db = DB::get_instance();
        $sql = $db->prepare("SELECT id FROM articles WHERE id = ? AND active = 1");
        $sql->execute([$id]);

        if ($sql->rowCount() > 0) {
          if ($replied_id != null && is_numeric(intval($replied_id)) && intval($replied_id) > 0) {
            
            $replied_id = abs(intval($replied_id));

            $sql = $db->prepare("SELECT id FROM comments WHERE id = ? AND reply_on IS NULL");
            $sql->execute([$replied_id]);
    
            if ($sql->rowCount() > 0) {

              $sql = $db->prepare(
                "INSERT INTO comments (comment, date, article, user, reply_on)
                VALUES (?, NOW(), ?, ?, ?)");
              $res = $sql->execute([$comment, $id, $user, $replied_id]);
              return $res;

            }else {
              return false;
            }

          }else {
            $sql = $db->prepare(
              "INSERT INTO comments (comment, date, article, user)
              VALUES (?, NOW(), ?, ?)");
            $res = $sql->execute([$comment, $id, $user]);
            return $res;
          }
        }else {
          return false;
        }
        
      }else {
        return false;
      }

    }

    public static function get_replies($comment_id, $offset = 1) {

      $id = self::valid_id($comment_id);
      
      // $offset = is_numeric(intval($offset)) ? intval($offset): 1;
      $offset = intval($offset);
      $offset = ($offset * 4) - 4;

      if (is_numeric($id)) {

        $db = DB::get_instance();
        $sql = $db->prepare(
          "SELECT C.id FROM comments C
          INNER JOIN articles A ON A.id = C.article
          WHERE C.id = ? AND A.active = 1 ORDER BY C.date DESC");
        $sql->execute([$id]);

        if ($sql->rowCount() > 0) {
          $sql = $db->prepare(
            "SELECT C.*, U.id, U.image AS user_image, U.name AS user_name FROM comments C
            INNER JOIN users U ON U.id = C.user WHERE reply_on = ? LIMIT 4 OFFSET $offset");
          $sql->execute([$id]);
          if ($sql->rowCount() > 0) {
            return ["success" => true, "data" => $sql->fetchAll(PDO::FETCH_CLASS, "Comment")];
          }else {
            return ["success" => false, "error" => "no replies!"];
          }
        }else {
          return false;
        }
        
      }else {
        return false;
      }

    }

    public static function add_favorite() {

      $db = DB::get_instance();
      $sql = $db->prepare("");
      $sql->execute([]);

      if ($sql->rowCount() > 0) {
      }else {
      }

    }
    
  }
?>