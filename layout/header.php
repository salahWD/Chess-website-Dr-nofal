<header class="header">
  <div class="container">
    <nav class="navbar">
      <div class="blur"></div>
      <div class="content">
        <div class="logo">
          <img src="<?php echo Router::route("img/logo.png");?>" alt="logo">
        </div>
        <button class="btn btn-transparent sm-visible jkit-hamburger-menu" id="header-btn">
          <i class="fas fa-bars"></i>
        </button>
        <div class="pages md-visible">
          <ul class="list-unstyled">
            <li>
              <a tabindex="-1" href="<?php echo MASTER_URL;?>" <?php echo $page=="home"?"class=\"active\"":"";?>>Home</a>
            </li>
            <li>
              <a tabindex="-1" href="<?php echo Router::route("about");?>" <?php echo $page=="about"?"class=\"active\"":"";?>>About Us</a>
            </li>
            <li>
              <a tabindex="-1" href="<?php echo Router::route("courses");?>" <?php echo $page=="courses"?"class=\"active\"":"";?>>courses</a>
            </li>
            <!-- <li class="menu">
              <a href="<?php echo Router::route("courses");?>" <?php echo $page=="courses"?"class=\"active\"":"";?>>courses <i class="jki jki-down-arrow1-light"></i></a>
              <ul class="menu-list list-unstyled">
                <li><a href="#">black peaces</a></li>
                <li><a href="#">white peaces</a></li>
              </ul>
            </li> -->
            <li>
              <a tabindex="-1" href="<?php echo Router::route("blog");?>" <?php echo $page=="blog"?"class=\"active\"":"";?>>Blog</a>
            </li>
            <li>
              <a tabindex="-1" href="<?php echo Router::route("contact");?>" <?php echo $page=="contact"?"class=\"active\"":"";?>>Contact</a>
            </li>
          </ul>
        </div>
        <div class="cta md-visible">
          <div class="btn btn-warning">Login Now</div>
        </div>
      </div>
    </nav>
    <div class="overlay sm-visible" id="header-overlay"></div>
    <ul class="list-unstyled sm-visible pages" id="header">
      <div class="logo">
        <img src="<?php echo Router::route("img/logo.png");?>" alt="logo">
      </div>
      <li>
        <a href="<?php echo MASTER_URL;?>" <?php echo $page=="home"?"class=\"active\"":"";?>>Home</a>
      </li>
      <li>
        <a href="<?php echo Router::route("about");?>" <?php echo $page=="about"?"class=\"active\"":"";?>>About Us</a>
      </li>
      <li>
        <a href="<?php echo Router::route("courses");?>" <?php echo $page=="courses"?"class=\"active\"":"";?>>courses</a>
      </li>
      <!-- <li class="menu">
        <a href="<?php echo Router::route("courses");?>" <?php echo $page=="courses"?"class=\"active\"":"";?>>courses <i class="jki jki-down-arrow1-light"></i></a>
        <ul class="menu-list list-unstyled">
          <li><a href="#">black peaces</a></li>
          <li><a href="#">white peaces</a></li>
        </ul>
      </li> -->
      <li>
        <a href="<?php echo Router::route("blog");?>" <?php echo $page=="blog"?"class=\"active\"":"";?>>Blog</a>
      </li>
      <li>
        <a href="<?php echo Router::route("contact");?>" <?php echo $page=="contact"?"class=\"active\"":"";?>>Contact</a>
      </li>
    </ul>
  </div>
</header>