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
      <pre>
        </pre>
        <?php $content = json_decode($article->content);?>
        <?php foreach($content->blocks as $block):?>
        <?php if ($block->type == "paragraph"):?>
          <p><?= $block->data->text;?></p>
        <?php elseif ($block->type == "header"):?>
          <h<?= $block->data->level;?>><?= $block->data->text;?></h<?= $block->data->level;?>>
        <?php elseif ($block->type == "image"):?>
          <img src="<?= $block->data->file->url;?>" alt="<?= $block->data->file->caption;?>">
        <?php endif;?>
      <?php endforeach;?>
      <?php //echo $article->content;?>
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
      <?php if (isset($user) && !empty($user)):?>
        <form action="<?= Router::route("add/comment");?>" method="POST">
          <div class="input-holder">
            <label for="comment">
              <div class="image">
                <img src="<?= Router::route("uploads/img/" . $user->image);?>" alt="">
              </div>
            </label>
            <textarea name="comment" id="comment" placeholder="Leave a comment"></textarea>
            <button class="btn btn-warning">send <i class="fa-solid fa-paper-plane"></i></button>
          </div>
        </form>
      <?php else:?>
        login
      <?php endif;?>
      <hr class="divider end">
      <?php if (isset($comments) && !empty($comments) && is_array($comments)):?>
        <?php foreach ( $comments as $comment ):?>
          <div class="comment">
            <div class="body">
              <div class="profile">
                <div class="user-img"><img src="<?= Router::route("uploads/img/$comment->user_image");?>" alt=""></div>
                <span class="user-name"><?= $comment->user_name;?></span>
              </div>
              <div class="text"><?= $comment->comment;?></div>
            </div>
            <div class="actions">
              <button title="replay" class="btn btn-transparent replay"><i class="fa-solid fa-reply"></i> <small>replay</small></button>
              <p class="date"><?= $comment->date;?></p>
            </div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</section>