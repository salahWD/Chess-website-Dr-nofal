<h2 class="title">Last Month Analytics</h2>
<div class="show-cards row">
  <div class="card created">
    <div class="info">
      <h3 class="number">4</h3>
      <p class="title">created courses</p>
    </div>
    <div class="icon course"><i class="fa-solid fa-book"></i></div>
  </div>
  <div class="card avg-like">
    <div class="info">
      <h3 class="number">33</h3>
      <p class="title">avrage enrollers</p>
    </div>
    <div class="icon"><i class="fa-solid fa-graduation-cap"></i></div>
  </div>
  <a class="card create" href="<?= Router::route("dashboard/course/create");?>">
    <div class="info">
      <div class="icon"><i class="fa-solid fa-plus"></i></div>
      <p class="title">create new course</p>
    </div>
  </a>
</div>
<div class="row">
  <div class="articles items-box">
    <h2 class="title">manage all courses</h2>
    <div class="table">
      <div class="header" id="manage-table" data-type="1" data-token="<?= $token;?>">
        <h3 class="title">title</h3>
        <h3 class="title">create date</h3>
        <h3 class="title">status</h3>
        <h3 class="title">actions</h3>
      </div>
      <?php if (is_array($courses) && count($courses) > 0):?>
        <?php foreach($courses as $course):?>
          <div class="item courses animate__animated">
            <p class="cell"><?= $course->title;?></p>
            <p class="cell"><?= $course->date;?></p>
            <?php $status = ["unpublish", "public"];?>
            <p class="cell"><?= $status[$course->active];?></p>
            <div class="cell">
              <a href="<?= Router::route("dashboard/course/$course->id");?>" class="btn btn-primary">edit</a>
              <button type="button" data-id="<?= $course->id;?>" class="btn btn-danger btn-remove-item">delete</button>
            </div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</div>