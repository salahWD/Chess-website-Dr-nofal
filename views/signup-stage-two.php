<section class="login user">
  <div class="container">
    <pre style="position: fixed;transform:translatey(-50%);top: 50%;left:20px;padding: 20px;background-color: white;margin-right:20px;">
      signup
      <?php var_dump($signup_errors);?>
      <hr>
        session
      <?php var_dump($_SESSION);?>
      <hr>
        signup info
      <?php var_dump($signup_info);?>
    </pre>
    <div class="form-container" id="form-container">
      <form action="<?= Router::route("signup");?>" method="POST" enctype="multipart/form-data">
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