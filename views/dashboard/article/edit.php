<form action="<?= Router::route("api/article");?>" method="POST">
  <div class="form row">
    <div class="image">
      <img id="image" src="<?= Router::route("uploads/img/$article->image");?>" alt="user image">
      <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
    </div>
    <div class="info">
      <div class="item date">
        <span class="icon">
          <i class="far fa-calendar-alt"></i>
        </span>
        <span class="text"><?= $article->date;?></span>
      </div>
      <div class="item writer">
        <span class="icon">
          <i class="far fa-user-circle"></i>
        </span>
        <span class="text"><?= $article->name;?></span>
      </div>
      <div class="item status">
        <span class="icon">
          <?php if ($article->active):?>
            <i class="fa-solid fa-eye"></i>
          <?php else:?>
            <i class="fa-solid fa-eye-slash"></i>
          <?php endif;?>
        </span>
        <?php $arr = ["Unpublished", "published"];?>
        <span class="text"><?= $arr[$article->active];?></span>
      </div>
    </div>
    <div class="input-box">
      <label for="title">title</label>
      <input type="text" name="article_title" id="title" value="<?= $article->title;?>" placeholder="Article Title">
      <p class="error" id="title-error"></p>
    </div>
    <div class="input-box">
      <label for="url-title" id="url-title-label">url title</label>
      <input type="text" name="url_title" id="url-title" value="<?= $article->url_title;?>" placeholder="exampil-for-url-title">
      <p class="error" id="url-title-error"></p>
    </div>
    <div class="input-container">
      <div class="input-box type">
        <label for="type">type</label>
        <input type="text" name="type" id="type" value="<?= $article->tag;?>" placeholder="article type">
        <p class="error" id="type-error"></p>
      </div>
      <div class="input-box">
        <label for="publish">publish</label>
        <?php $i = [["icon" => "fa-eye-slash", "class" => "btn-danger"], ["icon" => "fa-eye", "class" => "btn-primary"]];?>
        <?php $active = $article->active == 1 ? 1: 0;?>
        <button class="btn <?= $i[$active]["class"];?>" type="button" id="publish">public <i id="public-icon" class="fa-solid <?= $i[$active]["icon"];?>"></i></button>
        <input class="d-none" type="hidden" name="active" id="public-value" value="<?= $active;?>" />
      </div>
    </div>
    <div class="input-box">
      <label for="description">description</label>
      <textarea type="text" name="description" id="description" placeholder="write a short description"><?= $article->description;?></textarea>
    </div>
    <button type="button" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
  <div class="form row">
    <div class="input-box">
      <label for="content">content</label>
      <p class="error" id="content-error"></p>
      <div class="content" id="content"></div>
      <button type="button" class="btn btn-success" id="save-content">save <i class="fa-solid fa-floppy-disk"></i></button>
    </div>
  </div>
</form>