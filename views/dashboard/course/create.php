<form action="<?= Router::route("api/course");?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="action" value="create">
  <div class="form row">
    <div class="image">
      <img id="image" src="<?= Router::route("uploads/img/article-default.jpg");?>" alt="user image">
      <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
    </div>
    <div class="input-box <?= isset($errors["title"]) ? "is-wrong": "";?>">
      <label for="title">title</label>
      <input type="text" name="course_title" id="title" placeholder="Course Title" value="<?= $info_holder["title"] ?? "";?>" >
      <p class="error"><?= $errors["title"] ?? "";?></p>
    </div>
    <div class="input-box <?= isset($errors["price"]) ? "is-wrong": "";?>">
      <label for="price">price</label>
      <input type="number" name="price" id="price" placeholder="course price" value="<?= $info_holder["price"] ?? "";?>">
      <p class="error"><?= $errors["price"] ?? "";?></p>
    </div>
    <div class="input-box <?= isset($errors["description"]) ? "is-wrong": "";?>">
      <label for="description">description</label>
      <textarea type="text" name="description" id="description" placeholder="write a short description"><?= $info_holder["description"] ?? "";?></textarea>
      <p class="error"><?= $errors["description"] ?? "";?></p>
    </div>
    <button type="submit" name="action" value="create" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
</form>