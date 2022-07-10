<section class="login profile user">
  <div class="container">
    <div class="form-container" id="form-container">
      <form action="<?= Router::route("user/profile");?>" method="POST" enctype="multipart/form-data">
        <div class="form animate__animated animate__fadeIn">
          <h2 class="title w-100">Edit Profile</h2>
          <div class="row">
            <div class="image">
              <img id="image" src="<?= Router::route("uploads/img/$user->image");?>" alt="user image">
              <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
            </div>
            <div class="input-box <?= isset($errors["name"]) ? "is-wrong": "";?>">
              <label for="name">name (other peaple will see)</label>
              <input type="text" name="name" id="name" class="login-input" placeholder="name" value="<?= $user->name;?>">
              <p class="error"><?= $errors["name"] ?? "";?></p>
            </div>
            <div class="input-box <?= isset($errors["username"]) ? "is-wrong": "";?>">
              <label for="username">name (for login)</label>
              <input type="text" name="username" id="username" class="login-input" placeholder="username" value="<?= $user->username;?>">
              <p class="error"><?= $errors["username"] ?? "";?></p>
            </div>
            <div class="input-box <?= isset($errors["email"]) ? "is-wrong": "";?>">
              <label for="email">email</label>
              <input type="email" name="email" id="email" class="login-input" placeholder="email" value="<?= $user->email;?>">
              <p class="error"><?= $errors["email"] ?? "";?></p>
            </div>
            <div class="input-box <?= isset($errors["password"]) ? "is-wrong": "";?>">
              <label for="password">password</label>
              <input type="password" name="password" id="password" class="password-input" placeholder="password">
              <p class="error"><?= $errors["password"] ?? "";?></p>
            </div>
            <button type="submit" class="btn btn-warning">save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>