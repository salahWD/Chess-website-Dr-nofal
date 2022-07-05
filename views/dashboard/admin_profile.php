<?php $permission_levels = ["admin", "writer", "checker"];?>
<form id="user" action="<?= Router::route("admin/profile");?>" method="POST" enctype="multipart/form-data">
  <div class="form row">
    <div class="info">
      <div class="item image">
        <img id="image" src="<?= Router::route("uploads/img/$admin->image");?>" alt="user image">
        <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
      </div>
      <div class="details">
        <div class="item date">
          <span class="icon">
            <i class="fa-solid fa-user"></i>
          </span>
          <span class="text"><?= $permission_levels[0];?></span>
        </div>
      </div>
    </div>
    <div class="input-box">
      <label for="name">name (visible)</label>
      <input type="text" name="name" id="name" placeholder="name to show" value="<?= $admin->name;?>" >
      <p class="error" id="name-error"></p>
    </div>
    <div class="input-box">
      <label for="username">username (for login)</label>
      <input type="text" name="username" id="username" placeholder="username to login" value="<?= $admin->username;?>" >
      <p class="error" id="username-error"></p>
    </div>
    <div class="input-box">
      <label for="email">email</label>
      <input type="email" name="email" id="email" placeholder="user email" value="<?= $admin->get_email();?>" >
      <p class="error" id="email-error"></p>
    </div>
    <div class="input-box">
      <label for="passwor">password</label>
      <input type="password" name="password" id="password" placeholder="password" >
      <p class="error" id="password-error"></p>
    </div>

    <button type="submit" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
</form>