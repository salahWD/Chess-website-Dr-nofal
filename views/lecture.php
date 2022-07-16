<?php
  $page_info = [
    "title" => $lecture->course_title,
    "is_lecture"  => true,
    "background" => Router::route("/uploads/img/") . $lecture->image,
  ];

  include_once LAYOUT_PATH . "pages-header.php";
?>
<section class="lecture">
  <div class="container">
    <div class="text text-center">
      <div class="title"><?= $lecture->title;?></div>
      <p class="text-holder desc"><?= $lecture->description;?></p>
    </div>
    <?php if (isset($progress_info) && is_array($progress_info) && isset($watched) && is_array($watched)):?>
      <div class="progress-container">
        <div class="progress-bar">
          <div class="bar">
            <?php
              $count = 0;
              foreach ($progress_info["lectures"] as $lec) {
                if (in_array($lec->id, $watched)) {
                  $count += 1;
                }else {
                  break;
                }
              }?>
            <div class="value finish-<?= $count;?>"></div>
            <?php $trig = true;?>
            <?php foreach($progress_info["lectures"] as $lec):?>
              <a href="<?= Router::route("lecture/" . $lec->url_title);?>" class="circle <?= in_array($lec->id, $watched) ? "active": "";?> <?php if ($trig && $lec->id == $lecture->id) {echo "current";$trig=false;}?>">
                <h3 class="title"><?= $lec->title;?></h3>
                <h3 class="number"><?= $lec->order;?></h3>
              </a>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    <?php else:?>
      <div class="alert aler-danger">ther is no lecture info</div>
    <?php endif;?>
    <div class="lecture">
      <div class="video-container paused" data-volume-level="high">
        <img class="thumbnail-img">
        <div class="video-controls-container">
          <div class="timeline-container">
            <div class="timeline">
              <div id="preview-thumbnail" class="preview-thumbnail"></div>
              <div id="preview-time" class="preview-time">00:00</div>
              <div class="thumb-indicator"></div>
            </div>
          </div>
          <div class="controls">
            <button class="play-pause-btn">
              <svg class="play-icon" viewBox="0 0 24 24">
                <path fill="currentColor" d="M8,5.14V19.14L19,12.14L8,5.14Z" />
              </svg>
              <svg class="pause-icon" viewBox="0 0 24 24">
                <path fill="currentColor" d="M14,19H18V5H14M6,19H10V5H6V19Z" />
              </svg>
            </button>
            <div class="volume-container">
              <button class="mute-btn">
                <svg class="volume-high-icon" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.84 14,18.7V20.77C18,19.86 21,16.28 21,12C21,7.72 18,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16C15.5,15.29 16.5,13.76 16.5,12M3,9V15H7L12,20V4L7,9H3Z" />
                </svg>
                <svg class="volume-low-icon" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M5,9V15H9L14,20V4L9,9M18.5,12C18.5,10.23 17.5,8.71 16,7.97V16C17.5,15.29 18.5,13.76 18.5,12Z" />
                </svg>
                <svg class="volume-muted-icon" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M12,4L9.91,6.09L12,8.18M4.27,3L3,4.27L7.73,9H3V15H7L12,20V13.27L16.25,17.53C15.58,18.04 14.83,18.46 14,18.7V20.77C15.38,20.45 16.63,19.82 17.68,18.96L19.73,21L21,19.73L12,10.73M19,12C19,12.94 18.8,13.82 18.46,14.64L19.97,16.15C20.62,14.91 21,13.5 21,12C21,7.72 18,4.14 14,3.23V5.29C16.89,6.15 19,8.83 19,12M16.5,12C16.5,10.23 15.5,8.71 14,7.97V10.18L16.45,12.63C16.5,12.43 16.5,12.21 16.5,12Z" />
                </svg>
              </button>
              <input class="volume-slider" type="range" min="0" max="1" step="any" value="1">
            </div>
            <div class="duration-container">
              <div class="current-time">0:00</div>
              /
              <div class="total-time"></div>
            </div>
            <button class="speed-btn wide-btn">
              1x
            </button>
            <button class="full-screen-btn">
              <svg class="open" viewBox="0 0 24 24">
                <path fill="currentColor" d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
              </svg>
              <svg class="close" viewBox="0 0 24 24">
                <path fill="currentColor" d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>
              </svg>
            </button>
          </div>
        </div>
        <video src="<?= Router::route("uploads/courses/" . $lecture->video);?>">
        </video>
      </div>
      <div class="btns-container">
        <?php if (isset($progress_info["prev"]) && is_string($progress_info["prev"])):?>
          <a href="<?= Router::route("lecture/" . $progress_info["prev"]);?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> previus</a>
        <?php endif;?>
        <?php if (isset($progress_info["next"]) && is_string($progress_info["next"])):?>
          <a href="<?= Router::route("lecture/" . $progress_info["next"]);?>" class="btn btn-warning">next <i class="fa fa-arrow-right"></i></a>
        <?php endif;?>
      </div>
  </div>
</section>