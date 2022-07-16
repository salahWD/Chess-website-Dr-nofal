<?php
  $class = ["fadeInLeft", "fadeInDown", "fadeInRight"];
  $page_info = [
    "title" => "progress board",
    "is_course" => true
  ];
  include_once LAYOUT_PATH . "pages-header.php";
?>
<section class="lecture">
  <div class="container">
    <div class="text text-center">
      <div class="section-title">lectures</div>
      <div class="title"><?= $course->title;?></div>
      <p class="text-holder desc"><?= $course->description;?></p>
    </div>
    <div class="lectures">
      <?php if (isset($lectures) && is_array($lectures) && count($lectures) > 0):?>
        <?php $trig = true;?>
        <?php foreach($lectures as $i => $lecture):?>
          <div class="lecture <?= in_array($lecture->id, $watched) ? "active": "";?> <?php if ($trig && !in_array($lecture->id, $watched)) {echo "current";$trig = false;}?>">
            <div class="image">
              <img src="<?= Router::route("uploads/img/" . $lecture->image);?>" alt="">
              <h3 class="number"><?= $lecture->order;?></h3>
            </div>
            <div class="body">
              <h3 class="title"><?= $lecture->title;?></h3>
              <p class="desc"><?= substr($lecture->description, 0, 75);?></p>
              <p class="date"><?= $lecture->date;?></p>
              <a class="btn btn-block btn-warning" href="<?= Router::route("lecture/" . $lecture->url_title);?>">watch</a>
            </div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</section>