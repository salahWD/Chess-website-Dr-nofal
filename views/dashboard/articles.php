<div class="show-cards row">
  <div class="card created">
    <div class="info">
      <h3 class="number">12</h3>
      <p class="title">created articles</p>
    </div>
    <div class="icon"><i class="fa-solid fa-file-signature"></i></div>
  </div>
  <div class="card avg-like">
    <div class="info">
      <h3 class="number">17</h3>
      <p class="title">avrage likes</p>
    </div>
    <div class="icon"><i class="fa-solid fa-heart"></i></div>
  </div>
  <a class="card create" href="<?= Router::route("dashboard/article/create");?>">
    <div class="info">
      <div class="icon"><i class="fa-solid fa-plus"></i></div>
      <p class="title">create an article</p>
    </div>
  </a>
</div>
<div class="row">
  <div class="articles items-box">
    <h2 class="title">manage all articles</h2>
    <div class="table">
      <div class="header">
        <h3 class="title">title</h3>
        <h3 class="title">create date</h3>
        <h3 class="title">status</h3>
        <h3 class="title">actions</h3>
      </div>
      <?php foreach($articles as $article):?>
        <div class="item article">
          <p class="cell"><?= $article->title;?></p>
          <p class="cell"><?= $article->date;?></p>
          <?php $status = ["unpublish", "public"];?>
          <p class="cell"><?= $status[$article->active];?></p>
          <div class="cell">
            <a href="<?= Router::route("dashboard/article/$article->id");?>" class="btn btn-primary">edit</a>
            <a href="<?= Router::route("dashboard/article/delete");?>" class="btn btn-danger">delete</a>
          </div>
        </div>
      <?php endforeach;?>
    </div>
  </div>
</div>