<?php
  $page_info = [
    "title" => "Blog",
    "sub_page" => "Blog"
  ];
  include_once LAYOUT_PATH . "pages-header.php";
?>
<section class="blog">
  <div class="container">
    <div class="blogs animate__animated" data-animation="animate__fadeInDown">
      <?php foreach($articles as $article):?>
        <div class="card">
          <div class="card-img">
            <img src="<?php echo Router::route("img/") . $article->image;?>" alt="">
            <span class="type"><?php echo $article->tag;?></span>
          </div>
          <div class="card-info">
            <a class="title" href="<?php echo Router::route("blog/$article->url_title");?>">
              <h3 class="m-0"><?php echo $article->title;?></h3>
            </a>
            <span class="date"><i class="fas fa-calendar-alt"></i> <?php echo $article->date;?>April 21, 2022</span>
            <p class="desc"><?php echo $article->description;?></p>
            <a href="<?php echo Router::route("blog/$article->url_title");?>" class="btn c-prim btn-transparent">Read More <i class="jki jki-arrow-right-light"></i></a>
          </div>
        </div>
      <?php endforeach;?>
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