<section <?= isset($page_info["background"]) ? "style=\"background-image:url(" . $page_info['background'] . ")\"" :"";?> class="header background special-section">
  <div class="overlay"></div>
  <div class="container">
    <div class="text-center center-box">
      <h2 class="title cap animate__animated" data-animation="animate__fadeInUp"><?php echo $page_info["title"];?></h2>
      <ul class="path list-unstyled animate__animated" data-animation="animate__fadeInDown">
        <?php if (isset($page_info["is_artical"]) && $page_info["is_artical"] == true):?>
          <li class="icon"><i class="c-p far fa-user-circle"></i> <?php echo $page_info["info"]["writer"];?></li>
          <li class="icon"><i class="c-p far fa-calendar-alt"></i> <?php echo $page_info["info"]["date"];?></li>
          <li class="icon"><i class="c-p far fa-folder-open"></i> <?php echo $page_info["info"]["type"];?></li>
        <?php elseif (isset($page_info["is_course"]) && $page_info["is_course"] == true):?>
          <li class="icon"><i class="c-p fa-solid fa-layer-group"></i> course progress</li>
          <li class="icon"><i class="c-p fa-solid fa-wave-square"></i> learning path</li>
        <?php elseif (isset($page_info["is_lecture"]) && $page_info["is_lecture"] == true):?>
          <li class="icon"><i class="c-p fa-solid fa-video"></i> learning video</li>
          <li class="icon"><i class="c-p fa-solid fa-graduation-cap"></i> smooth learning experience</li>
        <?php else:?>
          <li class="page">home</li>
          <li class="page"><?php echo $page_info["sub_page"];?></li>
        <?php endif;?>
      </ul>
    </div>
  </div>
</section>
