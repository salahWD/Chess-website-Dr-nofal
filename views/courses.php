<?php
  $class = ["fadeInLeft", "fadeInDown", "fadeInRight"];
?>

<section class="header background special-section">
  <div class="overlay"></div>
  <div class="container">
    <div class="text-center center-box">
      <h2 class="title cap">Courses</h2>
      <ul class="path list-unstyled">
        <li class="page">home</li>
        <li class="page">about</li>
      </ul>
    </div>
  </div>
</section>

<section class="courses">
  <div class="container">
    <div class="text-center animate__animated" data-animation="animate__fadeInUp">
      <h4 class="section-title">Our Courses</h4>
      <h2 class="title">Best Courses And Learning</h2>
      <div class="p-holder-short">
        <p class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</p>
      </div>
    </div>
    <div class="courses">
      <?php foreach($courses as $i => $course):?>
        <div class="course animate__animated" data-animation="animate__<?php echo $class[$i % 3];?>">
          <div class="course-img">
            <img src="<?php echo $course->get_img_url();?>" title="course name goes here" alt="chess course image">
            <span class="price">
              <h3 class="number">$<?php echo $course->price;?></h3>
            </span>
          </div>
          <div class="course-info">
            <h4 class="course-title"><?php echo $course->title;?></h4>
            <div class="course-details">
              <div class="duration">
                <i class="icon fas fa-clock"></i>
                <span><?php echo $course->duration;?> Hours / meeting</span>
              </div>
              <div class="verify">
                <i class="icon fas fa-award"></i>
                <span>Certification</span>
              </div>
            </div>
            <hr class="divider">
            <div class="btn-rate">
              <div class="rating">
                <span class="rate"><?php echo $course->rate;?></span>
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fa-solid fa-star-half"></i>
                </div>
              </div>
              <a href="<?php echo MASTER_URL . "courses/$course->id";?>" class="btn btn-warning">Join Now</a>
            </div>
          </div>
        </div>
      <?php endforeach;?>
    </div>
  </div>
</section>


<section class="analytics">
  <div class="background-box special-section">
    <div class="background">
      <div class="container">
        <div class="boxes animate__animated" data-animation="animate__fadeIn">
          <div class="box">
            <i class="icon jki jki-team-1-light"></i>
            <div class="number"><span class="number" data-number="15" data-animation="3500">0</span><sup class="super">+</sup></div>
            <h3 class="title">Member Satisfaction</h3>
          </div>
          <div class="box">
            <i class="icon jki jki-trophy1-light"></i>
            <div class="number"><span class="number" data-number="15" data-animation="3500">0</span><sup class="super">+</sup></div>
            <h3 class="title">Winner Trophy</h3>
          </div>
          <div class="box">
            <i class="icon jki jki-team1-light"></i>
            <div class="number"><span class="number" data-number="15" data-animation="3500">0</span><sup class="super">+</sup></div>
            <h3 class="title">Professional Team</h3>
          </div>
          <div class="box">
            <i class="icon jki jki-badge-light"></i>
            <div class="number"><span class="number" data-number="15" data-animation="3500">0</span><sup class="super">+</sup></div>
            <h3 class="title">Many Courses</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>