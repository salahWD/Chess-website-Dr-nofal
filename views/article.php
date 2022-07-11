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
      <img src="<?php echo Router::route("uploads/img/") . $article->image;?>" alt="article image">
    </div>
    <div class="content">
      <?php $content = json_decode($article->content);?>
      <?php foreach($content->blocks as $block):?>
        <?php if ($block->type == "paragraph"):?>
          <p><?= $block->data->text;?></p>
        <?php elseif ($block->type == "header"):?>
          <h<?= $block->data->level;?>><?= $block->data->text;?></h<?= $block->data->level;?>>
        <?php elseif ($block->type == "image"):?>
          <figure class="<?= $block->data->stretched ? "stretched": "";?>">
            <img src="<?= $block->data->file->url;?>" alt="<?= $block->data->caption;?>">
            <figcaption><?= $block->data->caption;?>asdasd</figcaption>
          </figure>
        <?php endif;?>
      <?php endforeach;?>
    </div>
    <hr class="divider end">
    <div class="article-actions">
      <h3 class="title">do you like this article ?</h3>
      <div class="likes">
        <span class="number"><?= $article->likes;?></span>
        <?php if ($article->is_liked):?>
          <button id="like-btn" class="btn like btn-transparent active"><i class="icon fa-solid fa-heart"></i></button>
        <?php else:?>
          <button id="like-btn" class="btn like btn-transparent"><i class="icon fa-regular fa-heart"></i></button>
        <?php endif;?>
      </div>
    </div>
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
        <form action="<?= Router::route("add/comment");?>" id="comment-form" method="POST">
          <input type="hidden" name="article_id" value="<?= $article->id;?>">
          <div class="input-holder">
            <div class="body">
              <label for="comment">
                <div class="image">
                  <img src="<?= Router::route("uploads/img/" . $user->image);?>" alt="">
                </div>
                <span class="name">
                  <?= $user->name;?>
                </span>
              </label>
              <textarea name="comment" id="comment" placeholder="Leave a comment"></textarea>
              <button type="button" id="comment-send" data-value="send" class="btn btn-warning">send <i class="fa-solid fa-paper-plane"></i></button>
            </div>
              <p class="error animate__animated">Lorem ipsum dolor sit amet.</p>
          </div>
        </form>
      <?php else:?>
        <div class="input-holder">
          <div class="body">
            <textarea disabled name="comment" id="comment" placeholder="Login To Comment"></textarea>
            <a class="btn btn-warning login-btn" href="<?= Router::route("login");?>" class="btn btn-warning">Login</a>
          </div>
        </div>
      <?php endif;?>
      <hr class="divider end" style="margin-top: 20px!important; ">
      <div class="comments-container" id="comments">
        <?php if (isset($comments) && !empty($comments) && is_array($comments)):?>
          <?php foreach ($comments as $comment):?>
            <div class="comment">
              <input type="hidden" class="id" value="<?= $comment->id;?>">
              <div class="body">
                <div class="profile">
                  <div class="user-img"><img src="<?= Router::route("uploads/img/$comment->user_image");?>" alt=""></div>
                  <span class="user-name"><?= $comment->user_name;?></span>
                </div>
                <div class="text"><?= $comment->comment;?></div>
              </div>
              <div class="actions">
                <?php if (isset($user) && !empty($user)):?>
                  <button title="reply" class="btn btn-transparent reply reply-btn"><i class="fa-solid fa-reply"></i> reply</button>
                <?php else:?>
                    <a href="<?= Router::route("login");?>" class="btn login-btn btn-transparent reply"><i class="fa-solid fa-reply"></i> login to reply</a>
                <?php endif;?>
                <?php if ($comment->replies_count > 0):?>
                  <button title="show replies" class="btn btn-transparent show-replies-btn">view replies (<span class="number"><?= $comment->replies_count;?></span>)</button>
                  <?php else:?>
                    <button title="show replies" class="btn btn-transparent">no replies</button>
                <?php endif;?>
                <p class="date"><?= $comment->date;?></p>
              </div>
            </div>
          <?php endforeach;?>
        <?php endif;?>
      </div>
    </div>
  </div>
</section>