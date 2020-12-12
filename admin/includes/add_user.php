<?php

if (isset($_POST['create_user'])) {
  $user_firstname = escape($_POST['user_firstname']);
  $user_lastname = escape($_POST['user_lastname']);
  $user_role = escape($_POST['user_role']);
  $username = escape($_POST['username']);
  $user_email = escape($_POST['user_email']);
  $user_password = escape($_POST['user_password']);

  // $post_image = $_FILES['post_image']['name'];
  // $post_image_tempAddress = $_FILES['post_image']['tmp_name'];

  //move img from temp addrss to images folder with its name
  // move_uploaded_file($post_image_tempAddress, "../images/$post_image");

  // better way than above (cost is amount of iterations)
  $password = password_hash($user_password, PASSWORD_BCRYPT, ['cost' => 8]);

  $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) VALUES('$user_firstname','$user_lastname','$user_role','$username','$user_email','$password')";

  $create_user_query = mysqli_query($connection, $query);

  confirmQuery($create_user_query);

  echo "<i class='text-success'>User Created</i> " . "<a href='users.php'>View All Users</a><br><br>";
}
?>


<form action="" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label>Firstname</label>
    <input type="text" class="form-control" name="user_firstname">
  </div>
  <div class="form-group">
    <label>Lastname</label>
    <input type="text" class="form-control" name="user_lastname">
  </div>

  <div class="form-group">
    <label>Role</label>
    <select name="user_role" class="form-control">
      <option value='Admin'>Admin</option>
      <option value='Subscriber'>Subscriber</option>
    </select>
  </div>
  <!-- <div class="form-group">
    <label></label>
    <input type="file" class="form-control" name="post_image">
  </div> -->
  <div class="form-group">
    <label>Username</label>
    <input type="text" class="form-control" name="username">
  </div>
  <div class="form-group">
    <label>E-mail</label>
    <input type="email" name="user_email" class="form-control">
  </div>
  <div class="form-group">
    <label>Password</label>
    <input type="password" name="user_password" class="form-control">
  </div>
  <div class="form-group">
    <input type="submit" value="Add User" class="btn btn-primary" name="create_user">
  </div>

</form>