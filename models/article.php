<?php
  class Article {
    public $id;
    public $title;
    public $description;
    public $url_title;
    public $date;// date of publishing
    public $writer;
    public $name;// writer_name
    public $tag;// type or main tag
    public $tags;// tags of the article
    public $active;
    public $image = "article-default.jpg";
    public $image_uploaded = false;
    public $content;
    public $likes;// likes count
    public $is_liked = false;// is user liked this article

    private function isJson($string) {
      json_decode($string);
      return json_last_error() === JSON_ERROR_NONE;
    }

    public function set($data) {
      include_once CLASS_PATH . "formvalidate.php";
      $errors = [];
      $this->id = Form::valid_int($data["article_id"] ?? null);
      
      if (isset($data["article_title"]) && is_string($data["article_title"])) {
        if (strlen($data["article_title"]) > 4) {
          $this->title = Form::valid_str($data["article_title"] ?? "");
        }else {
          $errors["title"] = "title can't be less than 5 character";
        }
      }

      $url_title = isset($data["url_title"]) && !empty($data["url_title"]) ? $data["url_title"] : $data["article_title"];
      if (is_string($url_title)) {
        if (strlen($url_title) > 4) {
          $this->url_title = Form::valid_url_title($url_title);
        }else {
          $errors["url-title"] = "url-title can't be less than 5 character";
        }
      }

      if (isset($data["type"]) && is_string($data["type"])) {
        if (strlen($data["type"]) > 2) {
          $this->tag = Form::valid_str($data["type"]);
        }else {
          $errors["type"] = "type can't be less than 3 character";
        }
      }

      if (isset($data["description"]) && is_string($data["description"])) {
        $this->description = Form::valid_str($data["description"]);
      }else {
        $this->description = null;
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

      if (isset($data["content"]) && $this->isJson($data["content"])) {
        $this->content = $data["content"];
      }

      $this->active = isset($data["active"]) && intval($data["active"]) === 1 ? 1: 0;

      $info = [
				"title" 			=> $data["article_title"],
				"url_title" 	=> $data["url_title"],
				"type" 				=> $data["type"],
				"description" => $data["description"],
			];

      if (count($errors) > 0) {
        return ["success" => false, "errors" => $errors, "info_holder" => $info];
      }else {
        return ["success" => true];
      }

    }

    public function insert($writer_id) {
      // return [boolean: success, int: id, int: error]
      // error 1 => duplicate entry

      $db = DB::get_instance();
      
      $content_col = "";
      $content_param = "";
      $image_col = "";
      $image_param = "";
      
      $params_array = [
        ":title"        => $this->title,
        ":url_title"    => $this->url_title,
        ":description"  => $this->description,
        ":tag"          => $this->tag,
        ":writer"       => $writer_id,
        // ":image"        => $this->image
      ];

      if (!empty($this->content)) {
        $content_col = ",content";
        $content_param = ",:content";
        $params_array[":content"] = $this->content;
      }

      if ($this->image_uploaded) {
        $image_col = ", image";
        $image_param = ",:image";
        $params_array[":image"] = $this->image["new_name"];
        move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
      }
      
      $sql = $db->prepare("SELECT id FROM articles WHERE url_title = ?");
      $sql->execute([$this->url_title]);
      
      if ($sql->rowCount() == 0) {
        $sql = $db->prepare("INSERT INTO articles (`title`, `url_title`, `description`, `tag`, `writer`, `date`, `active` $image_col $content_col)
        VALUES (:title, :url_title, :description, :tag, :writer, NOW(), 0 $image_param $content_param)");
        $result = $sql->execute($params_array);
        if ($result) {
          return ["success" => true, "id" => $db->lastInsertId()];
        }else {
          return ["success" => false];
        }
      }else {
        return ["success" => false, "error" => ["url-title" => "there is another article with the same utl-title"]];// duplicate entry [error => 1]
      }

    }

    public function save() {
      // return [boolean: success, int: error]
      // error 1 => duplicate entry

      $db = DB::get_instance();
      
      $stmt = $db->prepare("SELECT id FROM articles WHERE url_title = ? AND id != ?");
      $stmt->execute([$this->url_title, $this->id]);

      if ($stmt->rowCount() == 0) {
      
        $execute = [
          ":title"      => $this->title,
          ":url_title"  => $this->url_title,
          ":desc"       => $this->description,
          ":tag"        => $this->tag,
          ":active"     => $this->active,
          ":content"    => $this->content,
          ":id"         => $this->id,
        ];

        $image_col = "";

        if ($this->image_uploaded) {
          $image_col = ", image = :image";
          $execute[":image"] = $this->image["new_name"];
          move_uploaded_file($this->image["tmp_name"], UPLOADS_IMAGES . $this->image["new_name"]);
        }
        
        $sql = "UPDATE articles SET title = :title, url_title = :url_title, description = :desc, tag = :tag,
        active = :active, content = :content $image_col WHERE id = :id";

        $stmt = $db->prepare($sql);
        $result = $stmt->execute($execute);
        
        return ["success" => $result];

      }else {
        return ["success" => false, "error" => "this url-title is used before!"];// duplicate entry [error => 1]
      }

    }

    public static function delete($id) {
      $db = DB::get_instance();
  
      $sql = $db->prepare("DELETE FROM articles WHERE id = ?");
      $res = $sql->execute([$id]);
      return ["success" => $res];
    }

    public static function get_article($id) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT A.id, A.title, A.url_title, A.tag, A.date, A.active, A.description,
        A.image, admins.name
        FROM articles A
        INNER JOIN admins ON A.writer = admins.id WHERE A.id = ?");
      $sql->execute([$id]);
      if ($sql->rowCount() > 0) {
        return $sql->fetchObject("Article");
      }else {
        return false;
      }
    }

    public static function get_content($id) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT articles.content FROM articles WHERE articles.id = ?");
      $sql->execute([$id]);
      if ($sql->rowCount() > 0) {
        return ["success" => true, "content" => $sql->fetch(PDO::FETCH_ASSOC)["content"], "error" => ""];
      }else {
        return ["success" => false, "content" => "", "error" => "2"];
      }
    }

    public static function get_errors_session() {
      $errors = null;
      if (isset($_SESSION["article/errors"])) {
        $errors = $_SESSION["article/errors"];
        unset($_SESSION["article/errors"]);
      }
      if ($errors != null && is_array($errors) && count($errors) > 0) {
        return $errors;
      }else {
        return null;
      }
    }
  
    public static function set_errors_session($data) {
      if (isset($_SESSION["article/errors"])) {
        unset($_SESSION["article/errors"]);
      }
      $_SESSION["article/errors"] = $data;
      return true;
    }
    
    public static function get_info_holder_session() {
      $info = null;
      if (isset($_SESSION["article/info"])) {
        $info = $_SESSION["article/info"];
        unset($_SESSION["article/info"]);
      }
      if ($info != null && is_array($info) && count($info) > 0) {
        return $info;
      }else {
        return null;
      }
    }
  
    public static function set_info_holder_session($data) {
      if (isset($_SESSION["article/info"])) {
        unset($_SESSION["article/info"]);
      }
      $_SESSION["article/info"] = $data;
      return true;
    }

    public static function get_comments($id) {
      
      $db = DB::get_instance();
      $sql = $db->prepare(
        "SELECT C.*, COUNT(B.id) AS replies_count, U.image AS user_image, U.name AS user_name FROM comments C
        LEFT JOIN comments B ON B.reply_on = C.id
        INNER JOIN users U ON U.id = C.user WHERE C.article = ? AND C.reply_on IS NULL GROUP BY C.id, B.reply_on ORDER BY date DESC");
      $sql->execute([$id]);
      if ($sql->rowCount() > 0) {
        include_once MODELS_PATH . "comment.php";
        return $sql->fetchAll(PDO::FETCH_CLASS, "Comment");
      }else {
        return false;
      }
    }

    public static function like($article_id, $user_id) {

      $res = false;
      $liked;
      $db = DB::get_instance();
      $sql = $db->prepare("SELECT * FROM article_likes WHERE user_id = ? AND article_id = ?");
      $sql->execute([$user_id, $article_id]);
      
      if ($sql->rowCount() > 0) {
        $sql = $db->prepare("DELETE FROM article_likes WHERE user_id = ? AND article_id = ?");
        $res = $sql->execute([$user_id, $article_id]);
        $liked = false;
      }else {
        $sql = $db->prepare("INSERT INTO article_likes (user_id, article_id) VALUES (?, ?)");
        $res = $sql->execute([$user_id, $article_id]);
        $liked = true;
      }

      return ["success" => $res, "liked" => $liked];

    }

    public function is_liked($user_id) {

      if (is_numeric(intval($user_id)) && intval($user_id) > 0) {

        $db = DB::get_instance();
        $sql = $db->prepare("SELECT * FROM article_likes WHERE user_id = ? AND article_id = ?");
        $sql->execute([$user_id, $this->id]);
  
        $res = $sql->rowCount() > 0;
        $this->is_liked = $res;
        return $res;

      }else {
        return false;
      }

    }

  }
?>