<?php
  $page_info = [
    "title" => $article->title,
    "is_artical"  => true,
    "info"  => ["writer" => $article->name, "date" => $article->date, "type" => $article->tag]
  ];

  include_once LAYOUT_PATH . "pages-header.php";
?>

<section class="article">
  <div class="container">
    <div class="image">
      <img src="<?php echo Router::route("img/") . $article->image;?>" alt="article image">
    </div>
    <div class="content">
      <?php echo $article->content;?>
    </div>
    <hr class="divider end">
    <div class="article-info">
      <p class="tags">
        Tags: 
        <?php foreach(explode(",", $article->tags) as $tag):?>
          <a href="<?php echo Router::route("blog/tag/$tag");?>"><?php echo $tag;?></a>
        <?php endforeach;?>
      </p>
      <div class="share">
        <p class="desc">Share This Article:</p>
        <div class="btns">
          <a href="#" class="btn btn-warning"><i class="jki jki-facebook-light"></i></a>
          <a href="#" class="btn btn-warning"><i class="fab fa-twitter"></i></a>
          <a href="#" class="btn btn-warning"><i class="jki jki-whatsapp-1-light"></i></a>
        </div>
      </div>
    </div>
    <div class="comments">
      <h4 class="title">comments:</h4>
      <?php if (isset($_SESSION["user"]) && !empty($_SESSION["user"])):?>
        <form>
          <div class="input-holder">
            <label for="comment">comment</label>
            <textarea name="comment" id="comment"></textarea>
          </div>
        </form>
      <?php else:?>
        login
      <?php endif;?>
      <?php if (isset($comments) && !empty($comments) && is_array($comments)):?>
        <?php foreach ( $comments as $comment ):?>
          <div class="comment">
            <div class="profile">
              <div class="user-img"><img src="<?= $comment->user_img ?>" alt=""></div>
              <span class="user-name"><?= $comment->user_name ?></span>
            </div>
            <div class="text"><?= $comment ?></div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</section>