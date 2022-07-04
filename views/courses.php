<?php
  $class = ["fadeInLeft", "fadeInDown", "fadeInRight"];
  $page_info = [
    "title" => "Courses",
    "sub_page" => "courses"
  ];
  include_once LAYOUT_PATH . "pages-header.php";
?>

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
      <?php if (is_array($courses) && count($courses) > 0):?>
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
                    <?php $count = $course->rate;?>
                    <?php for($i=0;ceil($course->rate) > $i;$i++):?>
                      <?php if (1 > $count):?>
                        <i class="fa-solid fa-star-half"></i>
                      <?php else:?>
                        <i class="fas fa-star"></i>
                      <?php endif;?>
                      <?php $count--;?>
                    <?php endfor;?>
                  </div>
                </div>
                <a href="<?php echo MASTER_URL . "course/$course->id";?>" class="btn btn-warning">Join Now</a>
              </div>
            </div>
          </div>
        <?php endforeach;?>
      <?php else:?>
        <h2 class="title">there's no courses yet.</h2>
      <?php endif;?>
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

<section class="testimonials">
  <div class="container">
    <div class="background">
      <i class="icon jki jki-quote-right-solid"></i>
      <div class="content">
        <div class="animate__animated" data-animation="animate__fadeInLeft">
          <h4 class="section-title">Our Testimonials</h4>
          <h2 class="title">What Are Student Says</h2>
        </div>
        <div class="testimonials-swiper testimons animate__animated" data-animation="animate__fadeInRight">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="box">
                  <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                  <div class="info">
                    <img src="./img/testimonials-01.jpg" alt="" class="profile-img">
                    <div class="stars">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star-half"></i>
                    </div>
                    <strong class="name">John Doe</strong>
                    <p class="job">Student</p>
                  </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="box">
                  <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                  <div class="info">
                    <img src="./img/testimonials-02.jpg" alt="" class="profile-img">
                    <div class="stars">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star-half"></i>
                    </div>
                    <strong class="name">Kelly Fred</strong>
                    <p class="job">Student</p>
                  </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="box">
                  <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                  <div class="info">
                    <img src="./img/testimonials-03.jpg" alt="" class="profile-img">
                    <div class="stars">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star-half"></i>
                    </div>
                    <strong class="name">Frederica Tan</strong>
                    <p class="job">Student</p>
                  </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="box">
                  <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                  <div class="info">
                    <img src="./img/testimonials-04.jpg" alt="" class="profile-img">
                    <div class="stars">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star-half"></i>
                    </div>
                    <strong class="name">Andre Sam</strong>
                    <p class="job">Student</p>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="instagram special-section">
  <div class="text-center animate__animated" data-animation="animate__fadeInUp">
    <a href="https://www.instagram.com/mohamed.nofal.dr.chess/"><h4 class="section-title">@mohamed.nofal.dr.chess</h4></a>
    <h3 class="title">Dr.nofal On Instagram</h3>
  </div>
  <div class="posts animate__animated" data-animation="animate__fadeInDown">
    <div class="post">
      <img src="./img/instagram-01.jpg" alt="">
      <i class="jki jki-instagram-1-light"></i>
    </div>
    <div class="post">
      <img src="./img/instagram-02.jpg" alt="">
      <i class="jki jki-instagram-1-light"></i>
    </div>
    <div class="post">
      <img src="./img/instagram-03.jpg" alt="">
      <i class="jki jki-instagram-1-light"></i>
    </div>
    <div class="post">
      <img src="./img/instagram-04.jpg" alt="">
      <i class="jki jki-instagram-1-light"></i>
    </div>
    <div class="post">
      <img src="./img/instagram-05.jpg" alt="">
      <i class="jki jki-instagram-1-light"></i>
    </div>
    <div class="post">
      <img src="./img/instagram-06.jpg" alt="">
      <i class="jki jki-instagram-1-light"></i>
    </div>
  </div>
</section>