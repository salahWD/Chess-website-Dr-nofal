<form id="user" action="<?= Router::route("api/course");?>" method="POST">
  <input type="hidden" name="token" value="<?= $token;?>">
  <div class="form row">
    <div class="info">
      <div class="item image">
        <img id="image" src="<?= Router::route("uploads/img/$user->image");?>" alt="user image">
        <button type="button" id="change-image" class="change-image btn btn-circle btn-primary"><i class="fa-solid fa-camera"></i></button>
      </div>
      <div class="details">
        <div class="item date">
          <span class="icon">
            <i class="far fa-calendar-alt"></i>
          </span>
          <span class="text"><?= $user->date;?></span>
        </div>
        <div class="item writer">
          <span class="icon">
            <i class="fa-regular fa-clock"></i>
          </span>
          <span class="text">Hour: <?= $user->date;?></span>
        </div>
      </div>
    </div>
    <div class="input-box">
      <label for="name">name (visible)</label>
      <input type="text" name="name" id="name" placeholder="name to show" value="<?= $user->name;?>" >
      <p class="error" id="name-error"></p>
    </div>
    <div class="input-box">
      <label for="username">username (for login)</label>
      <input type="text" name="username" id="username" placeholder="username to login" value="<?= $user->username;?>" >
      <p class="error" id="username-error"></p>
    </div>
    <div class="input-box">
      <label for="email">email</label>
      <input type="email" name="email" id="email" placeholder="user email" value="<?= $user->email;?>" >
      <p class="error" id="email-error"></p>
    </div>

    <button type="button" class="btn btn-success" id="save-info">save <i class="fa-solid fa-floppy-disk"></i></button>
  </div>
</form>