<h2 class="title">Last Month Analytics</h2>
<div class="show-cards row">
  <div class="card created">
    <div class="info">
      <h3 class="number">12</h3>
      <p class="title">signup users</p>
    </div>
    <div class="icon writed"><i class="fa-solid fa-user-plus"></i></div>
  </div>
  <div class="card avg-like">
    <div class="info">
      <h3 class="number">256</h3>
      <p class="title">total users</p>
    </div>
    <div class="icon writed"><i class="fa-solid fa-users"></i></div>
  </div>
</div>
<div class="row">
  <div class="users items-box">
    <h2 class="title">manage all users</h2>
    <div class="table" id="manage-table" data-type="2">
      <div class="header">
        <h3 class="title">image</h3>
        <h3 class="title">name</h3>
        <h3 class="title">email</h3>
        <h3 class="title">signup date</h3>
        <h3 class="title">actions</h3>
      </div>
      <?php if (is_array($users) && count($users) > 0):?>
        <?php foreach($users as $user):?>
          <div class="item article animate__animated">
            <div class="cell">
              <div class="picture">
                <div class="image">
                  <img src="<?= Router::route("uploads/img/" . $user->image);?>" alt="user image">
                </div>
              </div>
            </div>
            <p class="cell"><?= $user->name;?></p>
            <p class="cell"><?= $user->email;?></p>
            <p class="cell"><?= $user->date;?></p>
            <div class="cell">
              <a href="<?= Router::route("dashboard/user/$user->id");?>" class="btn btn-primary">edit</a>
              <button type="button" data-id="<?= $user->id;?>" class="btn btn-danger btn-remove-item">delete</button>
            </div>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    </div>
  </div>
</div>