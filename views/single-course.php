<?php
  $page_info = [
    "title" => "Course details",
    "sub_page" => "course"
  ];
  include_once LAYOUT_PATH . "pages-header.php";
?>

<section class="course">
  <div class="container">
    <div class="main">
      <div class="coach-info">
        <div class="text animate__animated" data-animation="animate__fadeInRight">
          <h2 class="title"><?php echo $course->title;?></h2>
          <p class="desc"><?php echo substr($course->description, 0, 45);?></p>
        </div>
        <div class="profile animate__animated" data-animation="animate__fadeInRight">
          <img src="<?php echo Router::route("uploads/img/" . $course->creator_image);?>" alt="coach profile image">
          <div class="info">
            <h4 class="name cap"><?= $course->creator_name;?></h4>
            <div class="rate animate__animated" data-animation="animate__fadeInRight">
              <span class="number">4.9</span>
              <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="image animate__animated" data-animation="animate__fadeInDown">
        <img src="<?php echo Router::route("uploads/img/$course->image");?>" alt="chess course image">
      </div>
      <div class="description animate__delay-02 animate__animated" data-animation="animate__fadeInLeft">
        <h3 class="title">Course Description</h3>
        <p class="desc"><?php echo $course->description;?></p>
      </div>
      <div class="what-learn animate__delay-04 animate__animated" data-animation="animate__fadeInDown">
        <h3 class="title">what you'll learn</h3>
        <div class="content">
          <div class="box video">
            <iframe class="video seamless" src="https://www.youtube-nocookie.com/embed/0y9Q4S2LkMk?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
          <div class="box text">
            <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus luctus.</p>
            <ul class="list-unstyled">
							<li>
                <i aria-hidden="true" class="c-p fas fa-check"></i><span>Experienced trainer</span>
              </li>
              <li class="elementor-icon-list-item">
                <i aria-hidden="true" class="c-p fas fa-check"></i><span>Chess playing strategy</span>
              </li>
              <li class="elementor-icon-list-item">
                <i aria-hidden="true" class="c-p fas fa-check"></i><span>Competitive practice</span>
              </li>
              <li class="elementor-icon-list-item">
                <i aria-hidden="true" class="c-p fas fa-check"></i><span>The basics of playing chess</span>
              </li>
						</ul>
          </div>
        </div>
      </div>
    </div>
    <div class="side">
      <div class="course-info card animate__animated" data-animation="animate__fadeInRight">
        <h2 class="title c-p">$<?php echo $course->price;?> <span class="month">/ Month</span></h2>
        <hr class="divider">
        <ul class="features-list list-unstyled">
          <li><i class="c-p fas fa-user"></i> Mentor : <span><?= $course->creator_name;?></span></li>
          <li><i class="c-p fas fa-clock"></i> Duration : 2 Hours / Videos</li>
          <li><i class="c-p fas fa-sort-amount-up-alt"></i> Level : Beginner</li>
          <li><i class="c-p fas fa-award"></i> Award : Certification</li>
          <li><i class="c-p fas fa-eye"></i> Lectures : <span><?= $course->lectures;?></span></li>
          <li><i class="c-p fas fa-eye"></i> Format : Off-Line</li>
        </ul>
        <a href="<?= Router::route("enroll/" . $course->id);?>" class="btn btn-warning">Enroll Now</a>
      </div>
      <div class="more-courses background animate__delay-02 animate__animated" data-animation="animate__fadeInRight">
        <div class="overlay"></div>
        <div class="content">
          <h3 class="title">View More Courses</h3>
          <p class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</p>
          <a href="<?php echo Router::route("courses");?>" class="c-p btn btn-transparent">view more <i aria-hidden="true" class="jki jki-arrow-right-solid"></i></a>
        </div>
      </div>
      <div class="offer">
        <h3 class="title animate__delay-04 animate__animated" data-animation="animate__fadeInRight">Join Our Class This Year And Get <span class="c-p">50%</span> Off !</h3>
        <div class="image animate__delay-05 animate__animated" data-animation="animate__fadeInDown">
          <img src="<?php echo Router::route("img/class-offer.png");?>" alt="chess offer image">
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
                    <img src="<?php echo Router::route("img/testimonials-01.jpg");?>" alt="" class="profile-img">
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
                    <img src="<?php echo Router::route("img/testimonials-02.jpg");?>" alt="" class="profile-img">
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
                    <img src="<?php echo Router::route("img/testimonials-03.jpg");?>" alt="" class="profile-img">
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
                    <img src="<?php echo Router::route("img/testimonials-04.jpg");?>" alt="" class="profile-img">
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