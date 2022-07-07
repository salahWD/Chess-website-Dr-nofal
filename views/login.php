<section class="login user">
  <div class="container">
    <div class="form-container" id="form-container">
      <form action="<?= Router::route("login");?>" method="POST">
        <div class="form animate__animated animate__fadeInLeft" id="form-login">
          <button type="button" class="btn toggle-btn" id="login-toggle">Sign Up</button>
          <h2 class="title w-100">Login</h2>
          <div class="row">
            <div class="input-box <?= isset($login_errors["login"]) ? "is-wrong": "";?>">
              <label for="login">username or email</label>
              <input type="text" name="login" id="login" class="login-input" placeholder="username or email" value="<?= $login_info["login"] ?? "";?>">
              <p class="error"><?= $login_errors["login"] ?? "";?></p>
            </div>
            <div class="input-box <?= isset($login_errors["password"]) ? "is-wrong": "";?>">
              <label for="password">password</label>
              <input type="text" name="password" id="password" class="password-input" placeholder="password">
              <p class="error"><?= $login_errors["password"];?></p>
            </div>
            <button type="submit" class="btn btn-warning">Login</button>
          </div>
        </div>
      </form>
      <form action="<?= Router::route("signup");?>" method="POST">
        <div class="form animate__animated" id="form-signup">
          <button type="button" class="btn right toggle-btn" id="signup-toggle">Log In</button>
          <h2 class="title w-100">sign up</h2>
          <div class="row">
            <div class="input-box <?= isset($signup_errors["username"]) ? "is-wrong": "";?>">
              <label for="sign_username">username</label>
              <input type="text" name="username" id="sign_username" class="login-input" placeholder="username or email" value="<?= $signup_info["username"] ?? "";?>">
              <p class="error"><?= $signup_errors["username"] ?? "";?></p>
            </div>
            <div class="input-box <?= isset($signup_errors["email"]) ? "is-wrong": "";?>">
              <label for="sign_email">email</label>
              <input type="emaill" name="email" id="sign_email" class="email-input" placeholder="email" value="<?= $signup_info["email"] ?? "";?>">
              <p class="error"><?= $signup_errors["email"] ?? "";?></p>
            </div>
            <div class="input-box <?= isset($signup_errors["password"]) ? "is-wrong": "";?>">
              <label for="sign_password">password</label>
              <input type="password" name="password" id="sign_password" class="password-input" placeholder="password">
              <p class="error"><?= $signup_errors["password"] ?? "";?></p>
            </div>
            <button type="submit" class="btn btn-warning">Login</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>