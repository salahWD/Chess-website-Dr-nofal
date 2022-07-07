<section class="login user">
  <div class="container">
    <div class="form-container" id="form-container">
      <form action="<?= Router::route("signup");?>" method="POST">
        <div class="form animate__animated animate__fadeIn" id="form-signup">
          <div class="row">
            <div class="input-box info <?= isset($signup_errors["image"]) ? "is-wrong": "";?>">
              <div class="item image">
                <img id="image" src="<?= IMG_URL . "unknown.jpg";?>" alt="user image">
                <button type="button" id="change-image" class="change-image btn btn-circle"><i class="fa-solid fa-camera"></i></button>
              </div>
            </div>
            <div class="input-box <?= isset($signup_errors["name"]) ? "is-wrong": "";?>">
              <label for="name">name</label>
              <input type="text" name="name" id="name" class="login-input" placeholder="name (peaple will see this name)" value="<?= $signup_info["name"] ?? "";?>">
              <p class="error"><?= $signup_errors["name"] ?? "";?></p>
            </div>
            <button type="submit" class="btn btn-warning">Login</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>