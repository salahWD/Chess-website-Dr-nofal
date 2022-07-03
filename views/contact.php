<?php
  $page_info = [
    "title" => "contact",
    "sub_page"  => "contact us",
  ];

  include_once LAYOUT_PATH . "pages-header.php";
?>

<section class="contact">
  <div class="container">
    <div class="card">
      <div class="info background">
        <div class="top">
          <h4 class="section-title">Contact Us</h4>
          <h2 class="title">Get a Quote</h2>
          <p class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
          <div class="details">
            <div class="item number">
              <i class="fas fa-phone-alt"></i>
              <a href="tel:0543434452">(+62) 81 2345 1234</a>
            </div>
            <div class="item email">
              <i class="fas fa-envelope"></i>
              <a href="mailto:gmmohamednofal@gmail.com">gmmohamednofal@gmail.com</a>
            </div>
            <div class="item location">
              <i class="fas fa-map-marker-alt"></i>
              <span>Egypt</span>
            </div>
          </div>
          <div class="spacer"></div>
        </div>
        <div class="btns">
          <a href="#" class="btn btn-warning"><i class="jki jki-facebook-light"></i></a>
          <a href="#" class="btn btn-warning"><i class="fab fa-twitter"></i></a>
          <a href="#" class="btn btn-warning"><i class="jki jki-whatsapp-1-light"></i></a>
        </div>
      </div>
      <form class="form">
        <div class="input-box">
          <label for="name">name</label>
          <input type="text" name="name" id="name" placeholder="Your Name">
        </div>
        <div class="input-box">
          <label for="email">email</label>
          <input type="email" name="email" id="email" placeholder="you@company.com">
        </div>
        <div class="input-box">
          <label for="subject">subject</label>
          <input type="text" name="subject" id="subject" placeholder="subject">
        </div>
        <div class="input-box">
          <label for="massage">massage</label>
          <textarea name="massage" id="massage" placeholder="massage"></textarea>
        </div>
        <button type="submit" class="btn btn-warning">send massage</button>
      </form>
    </div>
  </div>
</section>