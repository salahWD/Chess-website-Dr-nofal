<section class="login">
  <div class="container">
    <form action="<?= Router::route("dashboard/login");?>" method="POST">
    <h2 class="title">Welcome To Login</h2>
      <div class="form">
        <div class="row">
          <div class="input-box text-center <?= isset($errors) ? "is-wrong": "";?>">
            <?php if (is_array($errors) && count($errors) > 0):?>
              <?php foreach ($errors as $err):?>
                <p class="error"><?= $err;?></p>
              <?php endforeach;?>
            <?php endif;?>
          </div>
          <div class="input-box">
            <label for="username">username or email</label>
            <input type="text" name="login" id="login" class="login-input" placeholder="username or email">
          </div>
          <div class="input-box">
            <label for="password">password</label>
            <input type="text" name="password" id="password" class="password-input" placeholder="password">
          </div>
          <button type="submit" class="btn btn-warning">Login</button>
        </div>
      </div>
    </form>
  </div>
</section>