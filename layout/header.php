<?php $user = Router::get_user();?>
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
          <?php if ($user == null):?>
            <div class="cta md-visible">
              <a href="<?= Router::route("login");?>" class="btn btn-warning">Login Now</a>
            </div>
          <?php else:?>
            <div class="profile" id="profile-trigger">
              <h3 class="user-name"><?= $user->name;?></h3>
              <div class="image">
                <img src="<?= Router::route("uploads/img/{$user->image}");?>" alt="">
              </div>
              <div class="position-holder" id="profile">
                <div class="menu animate__animated">
                  <ul class="list list-unstyled">
                    <a href="<?= Router::route("user/profile");?>">
                      <li>
                        <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                        <span class="title">edit profile</span>
                      </li>
                    </a>
                    <a href="<?= Router::route("logout");?>">
                      <li>
                        <span class="icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                        <span class="title">logout</span>
                      </li>
                    </a>
                  </ul>
                </div>
              </div>
            </div>
          <?php endif;?>
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