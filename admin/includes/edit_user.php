<?php

//when user clicks on edit button then write data of that user in form
if (isset($_GET['edit'])) {
  $the_user_id = escape($_GET['edit']);

  $query = "SELECT * FROM users WHERE user_id = {$the_user_id}";
  $select_user_by_id = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($select_user_by_id)) {
    $user_firstname = escape($row['user_firstname']);
    $user_lastname = escape($row['user_lastname']);
    $user_role = escape($row['user_role']);
    $username = escape($row['username']);
    $user_email = escape($row['user_email']);
    $user_password = escape($row['user_password']);
  }

  // //this and above will happen when user clicks edit button
  // $user_password = crypt($user_password, $user_password);    //decrypt


  //this will happen when user clicks update button
  //extract all fields from forms and update Database
  if (isset($_POST['update_user'])) {
    $new_firstname = escape($_POST['user_firstname']);
    $new_lastname = escape($_POST['user_lastname']);
    $new_role = escape($_POST['user_role']);
    $new_username = escape($_POST['username']);
    $new_email = escape($_POST['user_email']);
    $new_password = escape($_POST['user_password']);

    // move_uploaded_file($post_image_tempAddress, "../images/$post_image");
    // if (empty($post_image)) {
    //   $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
    //   $select_image = mysqli_query($connection, $query);
    //   while ($row = mysqli_fetch_assoc($select_image)) {
    //     $post_image = $row['post_image'];
    //   }
    // }


    // lets create new salt everytime new user registers (strong passwords)
    // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./';
    // $salt = '$2y$14$';
    // for ($i = 0; $i < 22; $i++) {
    //   $index = rand(0, strlen($characters) - 1);    //generate rand number
    //   $salt .= $characters[$index];                 //concat salt and num
    // }
    // $new_password = crypt($new_password, $salt);    //encrypt


    $query = "UPDATE users SET user_firstname = '$new_firstname', user_lastname = '$new_lastname', user_role = '$new_role', username = '$new_username', user_email = '$new_email' WHERE  user_id=$the_user_id";

    $update_user = mysqli_query($connection, $query);
    confirmQuery($update_user);
    header("Location: users.php");    //move to posts page

    if (!empty($_POST['user_password'])) {

      // better way than above (cost is amount of iterations)
      $password = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 8]);

      $query = "UPDATE users SET user_password = '$password' WHERE  user_id=$the_user_id";
      $update_user_password = mysqli_query($connection, $query);
      confirmQuery($update_user_password);

      echo "User Updated <a href='users.php'>View Users</a>";
    }
  }
} else {
  header("Location: index.php");
}
?>

<form action="" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label>Firstname</label>
    <input value="<?php echo $user_firstname ?>" type="text" class="form-control" name="user_firstname">
  </div>
  <div class="form-group">
    <label>Lastname</label>
    <input value="<?php echo $user_lastname ?>" type="text" class="form-control" name="user_lastname">
  </div>

  <div class="form-group">
    <label>Role</label>
    <select name="user_role" class="form-control">
      <option value='<?php echo $user_role; ?>'><?php echo $user_role; ?></option>

      <?php
      if ($user_role == "Admin") {
        echo "<option value='Subscriber'>Subscriber</option>";
      } else {
        echo "<option value='Admin'>Admin</option>";
      }
      ?>

    </select>
  </div>
  <!-- <div class="form-group">
    <label></label>
    <input type="file" class="form-control" name="post_image">
  </div> -->
  <div class="form-group">
    <label>Username</label>
    <input value="<?php echo $username ?>" type="text" class="form-control" name="username">
  </div>
  <div class="form-group">
    <label>E-mail</label>
    <input value="<?php echo $user_email ?>" type="email" name="user_email" class="form-control">
  </div>
  <div class="form-group">
    <label>Password </label>
    <input type="password" name="user_password" class="form-control" autocomplete="off">
    <small style="color: grey;"> *enter new password here if u want to change</small>
  </div>
  <div class="form-group">
    <input type="submit" value="Update User" class="btn btn-primary" name="update_user">
  </div>

</form>