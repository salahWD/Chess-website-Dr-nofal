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
    public $image;
    public $content;
    public $likes;// likes count

    public function set($data) {
      include_once CLASS_PATH . "formvalidate.php";
      
      $this->id           = Form::valid_int($data["article_id"] ?? null);
      $this->title        = Form::valid_str($data["article_title"] ?? "");
      $url_title          =  isset($data["url_title"]) && !empty($data["url_title"]) ? $data["url_title"] : $data["article_title"];
      $this->url_title    = Form::valid_url_title($url_title);
      $this->tag          = Form::valid_str($data["type"] ?? "");
      $this->description  = Form::valid_str($data["description"] ?? "");
      $this->image        = $data["image"] ?? null;
      $this->content      = $data["content"] ?? "";
      $this->active       = isset($data["active"]) && intval($data["active"]) === 1 ? 1: 0;
    }

    public function insert($writer_id) {
      // return [boolean: success, int: id, int: error]
      // error 1 => duplicate entry

      $db = DB::get_instance();
      
      $content_col = "";
      $content_param = "";
      
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

      $sql = $db->prepare("SELECT id from articles WHERE url_title = ?");
      $sql->execute([$this->url_title]);

      if ($sql->rowCount() == 0) {
        $sql = $db->prepare("INSERT INTO articles (`title`, `url_title`, `description`, `tag`, `writer`, `date`, `active` $content_col)
        VALUES (:title, :url_title, :description, :tag, :writer, NOW(), 0 $content_param)");
        $result = $sql->execute($params_array);
        if ($result) {
          return ["success" => true, "id" => $db->lastInsertId()];
        }else {
          return false;
        }
      }else {
        return ["success" => false, "error" => 1];// duplicate entry [error => 1]
      }

    }

    public function save() {
      // return [boolean: success, int: error]
      // error 1 => duplicate entry

      $db = DB::get_instance();
      
      $stmt = $db->prepare("SELECT id FROM articles WHERE url_title = ? AND id != ?");
      $stmt->execute([$this->url_title, $this->id]);

      if ($stmt->rowCount() == 0) {
      
        $sql = "UPDATE articles SET title = :title, url_title = :url_title, description = :desc, tag = :tag,
        active = :active, content = :content WHERE id = :id";// , image = :image

        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
          ":title"      => $this->title,
          ":url_title"  => $this->url_title,
          ":desc"       => $this->description,
          ":tag"        => $this->tag,
          ":active"     => $this->active,
          ":content"    => $this->content,
          ":id"         => $this->id,
        ]);
        
        return ["success" => $result];

      }else {
        return ["success" => false, "error" => 1];// duplicate entry [error => 1]
      }

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

  }
?>