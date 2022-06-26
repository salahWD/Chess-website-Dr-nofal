<header class="header">
  <div class="container">
    <nav class="navbar">
      <div class="content">
        <div class="logo">
          <img src="./img/logo.png" alt="logo">
        </div>
        <div class="pages">
          <ul class="list-unstyled">
            <li>
              <a href="<?php echo MASTER_URL;?>" <?php echo $page=="home"?"class=\"active\"":"";?>>Home</a>
            </li>
            <li>
              <a href="<?php echo Router::route("about");?>" <?php echo $page=="about"?"class=\"active\"":"";?>>About Us</a>
            </li>
            <li class="menu">
              <a href="<?php echo Router::route("courses");?>" <?php echo $page=="courses"?"class=\"active\"":"";?>>courses <i class="jki jki-down-arrow1-light"></i></a>
              <ul class="menu-list list-unstyled">
                <li><a href="#">black peaces</a></li>
                <li><a href="#">white peaces</a></li>
              </ul>
            </li>
            <li>
              <a href="#">Pages</a>
            </li>
            <li>
              <a href="#">Blog</a>
            </li>
            <li>
              <a href="#">Contact</a>
            </li>
          </ul>
        </div>
        <div class="cta">
          <div class="btn btn-warning">Get Started</div>
        </div>
      </div>
    </nav>
  </div>
</header>