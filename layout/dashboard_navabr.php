<nav class="navbar">
  <div class="logo">
    <img src="<?= Router::route("img/logo.png");?>" alt="">
  </div>
  <ul class="items-list list-unstyled">
    <a href="<?= Router::route("dashboard");?>" <?= isset($page) && $page == "dashboard/index" ? 'class="active"': '';?>>
      <li>
        <span class="icon"><i class="fa-solid fa-house"></i></span>
        <span class="title">dashboard</span>
      </li>
    </a>
    <a href="<?= Router::route("dashboard/articles");?>" <?= isset($page) && str_contains($page, "/article") ? 'class="active"': '';?>>
      <li>
        <span class="icon"><i class="fa-solid fa-feather"></i></span>
        <span class="title">articles</span>
      </li>
    </a>
    <a href="<?= Router::route("dashboard/courses");?>" <?= isset($page) && str_contains($page, "/course") ? 'class="active"': '';?>>
      <li>
        <span class="icon"><i class="fa-solid fa-graduation-cap"></i></span>
        <span class="title">courses</span>
      </li>
    </a>
    <a href="<?= Router::route("dashboard/users");?>" <?= isset($page) && str_contains($page, "/user") ? 'class="active"': '';?>>
      <li>
        <span class="icon"><i class="fa-solid fa-users"></i></span>
        <span class="title">users</span>
      </li>
    </a>
  </ul>
</nav>
<div class="main">
  <div class="container">
    <nav class="header">
      <button id="navbar-trigger" class="btn btn-transparent"><i class="fa-solid fa-bars"></i></button>
      <div class="profile" id="profile-trigger">
        <h3 class="admin-name"><?= $admin->name;?></h3>
        <div class="image">
          <img src="<?= Router::route("uploads/img/{$admin->image}");?>" alt="">
        </div>
        <div class="position-holder" id="profile">
          <div class="menu animate__animated">
            <ul class="list list-unstyled">
              <a href="<?= Router::route("admin/profile");?>">
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
    </nav>