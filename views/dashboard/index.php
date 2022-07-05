<h2 class="title">Last Month Analytics</h2>
<div class="show-cards row">
  <div class="card">
    <div class="info">
      <h3 class="number">1345</h3>
      <p class="title">members</p>
    </div>
    <div class="icon user"><i class="fa-solid fa-user-plus"></i></div>
  </div>
  <div class="card">
    <div class="info">
      <h3 class="number">863</h3>
      <p class="title">comments</p>
    </div>
    <div class="icon comment"><i class="fa-regular fa-comments"></i></div>
  </div>
  <div class="card">
    <div class="info">
      <h3 class="number">17</h3>
      <p class="title">articles</p>
    </div>
    <div class="icon writed"><i class="fa-solid fa-feather"></i></div>
  </div>
  <div class="card">
    <div class="info">
      <h3 class="number">17</h3>
      <p class="title">likes</p>
    </div>
    <div class="icon like"><i class="fa-solid fa-heart"></i></i></div>
</div>
<div class="row recent">
  <div class="comments-box items-box">
    <h2 class="title">Recent Comments</h2>
    <div class="table">
      <div class="header">
        <h3 class="title">picture</h3>
        <h3 class="title">username</h3>
        <h3 class="title">comment</h3>
        <h3 class="title">aticle</h3>
      </div>
      <?php if (isset($comments) && is_array($comments) && count($comments) > 0):?>
        <?php foreach($comments as $comment):?>
          <div class="comment item">
            <div class="cell picture">
              <div class="image">
                <img src="<?= Router::route("uploads/img/" . $comment->user_image);?>" alt="">
              </div>
            </div>
            <p class="cell username"><?= substr($comment->user_name,0,20);?></p>
            <div class="cell text">
              <?= substr($comment->comment,0, 65);?>
              <?= strlen($comment->comment) > 65 ? "...": "";?>
            </div>
            <div class="cell article">
              <?= substr($comment->article_title,0, 40);?>
              <?= strlen($comment->article_title) > 40 ? "...": "";?>
            </div>
          </div>
        <?php endforeach?>
      <?php endif?>
    </div>
  </div>
  <div class="users-box items-box">
    <h2 class="title">Recent Users</h2>
    <div class="users">
      <div class="table">
        <?php if (isset($users) && is_array($users) && count($users) > 0):?>
          <?php foreach ($users as $user):?>
            <div class="user">
              <div class="image">
                <img src="<?= Router::route("uploads/img/" . $user->image);?>" alt="user image">
              </div>
              <div class="info">
                <h3 class="name"><?= substr($user->name,0,20);?></h3>
                <p class="date"><?= $user->date;?></p>
              </div>
            </div>
          <?php endforeach;?>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>