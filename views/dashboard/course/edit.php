<form id="course" action="<?= Router::route("api/course");?>" method="POST">
  <div class="form row">
    <div class="image">
      <img id="image" src="<?= Router::route("uploads/img/$course->image");?>" alt="user image">
      <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
    </div>
    <div class="info">
      <div class="item date">
        <span class="icon">
          <i class="far fa-calendar-alt"></i>
        </span>
        <span class="text"><?= $course->date;?></span>
      </div>
      <div class="item writer">
        <span class="icon">
          <i class="fa-regular fa-clock"></i>
        </span>
        <span class="text">Hour: <?= $course->duration;?></span>
      </div>
      <div class="item status">
        <span class="icon">
          <?php if ($course->active):?>
            <i class="fa-solid fa-eye"></i>
          <?php else:?>
            <i class="fa-solid fa-eye-slash"></i>
          <?php endif;?>
        </span>
        <?php $arr = ["Unpublished", "published"];?>
        <span class="text"><?= $arr[$course->active];?></span>
      </div>
    </div>
    <div class="input-box">
      <label for="title">title</label>
      <input type="text" name="course_title" id="title" placeholder="Course Title" value="<?= $course->title;?>" >
      <p class="error" id="title-error"></p>
    </div>
    <div class="input-container">
      <div class="input-box price">
        <label for="price">price</label>
        <input type="number" name="price" id="price" value="<?= $course->price;?>" placeholder="course price" value="<?= $course->price;?>">
        <p class="error" id="price-error"></p>
      </div>
      <div class="input-box">
        <label for="publish">publish</label>
        <?php $i = [["icon" => "fa-eye-slash", "class" => "btn-danger"], ["icon" => "fa-eye", "class" => "btn-primary"]];?>
        <?php $active = $course->active == 1 ? 1: 0;?>
        <button class="btn <?= $i[$active]["class"];?>" type="button" id="publish">public <i id="public-icon" class="fa-solid <?= $i[$active]["icon"];?>"></i></button>
        <input class="d-none" type="hidden" name="active" id="public-value" value="<?= $active;?>" />
      </div>
    </div>

    <div class="input-box">
      <label for="description">description</label>
      <textarea type="text" name="description" id="description" placeholder="write a short description"><?= $course->description;?></textarea>
      <p class="error" id="description-error"></p>
    </div>
    <button type="button" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
</form>