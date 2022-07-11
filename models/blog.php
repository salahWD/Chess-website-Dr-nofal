<?php
class Blog {

  public static function get_active_articles() {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT * FROM articles WHERE articles.active = ?");
    $sql->execute([1]);
    if ($sql->rowCount() > 0) {
      include_once MODELS_PATH . "article.php";
      return $sql->fetchAll(PDO::FETCH_CLASS, "Article");
    }else {
      return false;
    }
  }

  public static function get_article_title($title = "no-title") {
    $db = DB::get_instance();
    $sql = $db->prepare(
      "SELECT articles.*, admins.name, COUNT(LIKES.article_id) AS likes FROM articles
      INNER JOIN admins ON admins.id = articles.writer
      LEFT JOIN article_likes LIKES ON LIKES.article_id = articles.id
      WHERE articles.url_title = ?");
    $sql->execute([$title]);
    if ($sql->rowCount() > 0) {
      include_once MODELS_PATH . "article.php";
      return $sql->fetchObject("Article");
    }else {
      return false;
    }
  }

  public static function get_all_article() {
    $db = DB::get_instance();
    $sql = $db->prepare("SELECT A.id, A.title, A.date, A.active FROM articles A ORDER BY `date`");
    $sql->execute();
    if ($sql->rowCount() > 0) {
      include_once MODELS_PATH . "article.php";
      return $sql->fetchAll(PDO::FETCH_CLASS, "Article");
    }else {
      return false;
    }
  }

}
?>