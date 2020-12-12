<?php include "includes/admin_header.php" ?>

<?php

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];

  $query = "SELECT * FROM users WHERE user_email = '{$email}'";
  $select_user_profile = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($select_user_profile)) {
    $user_id = escape($row['user_id']);
    $username = escape($row['username']);
    $user_password = escape($row['user_password']);
    $user_firstname = escape($row['user_firstname']);
    $user_lastname = escape($row['user_lastname']);
    $user_email = escape($row['user_email']);
    $user_role = escape($row['user_role']);
    $user_image = escape($row['user_image']);
  }
}

if (isset($_POST['update_user'])) {
  $new_firstname = escape($_POST['user_firstname']);
  $new_lastname = escape($_POST['user_lastname']);
  $new_username = escape($_POST['username']);
  $new_email = escape($_POST['user_email']);
  $new_password = escape($_POST['user_password']);

  $query = "UPDATE users SET user_firstname = '$new_firstname', user_lastname = '$new_lastname', username = '$new_username', user_email = '$new_email' WHERE  user_id=$user_id";

  $update_user = mysqli_query($connection, $query);
  confirmQuery($update_user);
  header("Location: users.php");    //move to posts page

  if (!empty($_POST['user_password'])) {

    // better way of pass decrypt (cost is amount of iterations)
    $password = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 8]);


    $query = "UPDATE users SET user_password = '$password' WHERE  user_id=$the_user_id";

    $update_user_password = mysqli_query($connection, $query);

    confirmQuery($update_user_password);
    header("Location: users.php");    //move to posts page
  }
}


?>


<div id="wrapper">
  <!-- Navigation -->
  <?php include "includes/admin_navigation.php" ?>

  <div id="page-wrapper">
    <div class="container-fluid">

      <!-- Page Heading -->
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            Welcome To Admin
            <small>Author</small>
          </h1>


          <form action="" method="POST" enctype="multipart/form-data">

            <div class="form-group">
              <label>Firstname</label>
              <input value="<?php echo $user_firstname; ?>" type="text" class="form-control" name="user_firstname">
            </div>
            <div class="form-group">
              <label>Lastname</label>
              <input value="<?php echo $user_lastname; ?>" type="text" class="form-control" name="user_lastname">
            </div>

            <!-- <div class="form-group">
    <label></label>
    <input type="file" class="form-control" name="post_image">
  </div> -->
            <div class="form-group">
              <label>Username</label>
              <input value="<?php echo $username; ?>" type="text" class="form-control" name="username">
            </div>
            <div class="form-group">
              <label>E-mail</label>
              <input value="<?php echo $user_email; ?>" type="email" name="user_email" class="form-control">
            </div>
            <div class="form-group">
              <label>Password </label>
              <input type="password" name="user_password" class="form-control" autocomplete="off">
              <small style="color: grey;"> *enter new password here if u want to change</small>
            </div>
            <div class="form-group">
              <input type="submit" value="Update Profile" class="btn btn-primary" name="update_user">
            </div>

          </form>

        </div>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

  </div>
  <!-- /#page-wrapper -->
  <?php include "includes/admin_footer.php" ?>