<form action="<?= Router::route("api/article");?>" method="POST">
  <input type="hidden" name="token" value="<?= $token;?>">
  <input type="hidden" name="action" value="create">
  <div class="form row">
    <div class="input-box">
      <label for="title">title</label>
      <input type="text" name="article_title" id="title" placeholder="Article Title" value="<?= $article->title ?? "";?>" >
    </div>
    <div class="input-box <?= $errors == 1 ? "is-wrong": "";?>">
      <label for="url-title" id="url-title-label">url title</label>
      <input type="text" name="url_title" id="url-title" placeholder="exampil-for-url-title" value="<?= $article->url_title ?? "";?>">
      <p class="error" id="url-title-error">this entry have to be unique but it had been used before</p>
    </div>
    <div class="input-box">
      <label for="type">type</label>
      <input type="text" name="type" id="type" placeholder="article type" value="<?= $article->tag ?? "";?>">
    </div>
    <div class="input-box">
      <label for="description">description</label>
      <textarea type="text" name="description" id="description" placeholder="write a short description"><?= $article->description ?? "";?></textarea>
    </div>
    <button type="submit" name="create" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
</form>