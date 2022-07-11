<h2 class="title">Last Month Analytics</h2>
<div class="show-cards row">
  <div class="card created">
    <div class="info">
      <h3 class="number">12</h3>
      <p class="title">created articles</p>
    </div>
    <div class="icon writed"><i class="fa-solid fa-file-signature"></i></div>
  </div>
  <div class="card avg-like">
    <div class="info">
      <h3 class="number">17</h3>
      <p class="title">avrage likes</p>
    </div>
    <div class="icon like"><i class="fa-solid fa-heart"></i></div>
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
    <div class="table" id="manage-table" data-type="0">
      <div class="header">
        <h3 class="title">title</h3>
        <h3 class="title">create date</h3>
        <h3 class="title">status</h3>
        <h3 class="title">actions</h3>
      </div>
      <?php if (is_array($articles) && count($articles) > 0):?>
        <?php foreach($articles as $article):?>
          <div class="item article animate__animated">
            <p class="cell"><?= $article->title;?></p>
            <p class="cell"><?= $article->date;?></p>
            <?php $status = ["unpublish", "public"];?>
            <p class="cell"><?= $status[$article->active];?></p>
            <div class="cell">
              <a href="<?= Router::route("dashboard/article/$article->id");?>" class="btn btn-primary">edit</a>
              <button type="button" data-id="<?= $article->id;?>" class="btn btn-danger btn-remove-item">delete</button>
            </div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</div>