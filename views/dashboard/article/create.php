<form action="<?= Router::route("api/article");?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="action" value="create">
  <div class="form row">
    <div class="image">
      <img id="image" src="<?= Router::route("uploads/img/article-default.jpg");?>" alt="user image">
      <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
    </div>
    <div class="input-box <?= isset($errors["title"]) ? "is-wrong": "";?>">
      <label for="title">title</label>
      <input type="text" name="article_title" id="title" placeholder="Article Title" value="<?= $article["title"] ?? "";?>" >
      <p class="error"><?= $errors["title"] ?? "";?></p>
    </div>
    <div class="input-box <?= isset($errors["url-title"]) ? "is-wrong": "";?>">
      <label for="url-title" id="url-title-label">url title</label>
      <input type="text" name="url_title" id="url-title" placeholder="exampil-for-url-title" value="<?= $article["url_title"] ?? "";?>">
      <p class="error"><?= $errors["url-title"];?></p>
    </div>
    <div class="input-box <?= isset($errors["type"]) ? "is-wrong": "";?>">
      <label for="type">type</label>
      <input type="text" name="type" id="type" placeholder="article type" value="<?= $article["type"] ?? "";?>">
      <p class="error"><?= $errors["type"];?></p>
    </div>
    <div class="input-box">
      <label for="description">description</label>
      <textarea type="text" name="description" id="description" placeholder="write a short description"><?= $article["description"] ?? "";?></textarea>
    </div>
    <button type="submit" name="create" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
</form>