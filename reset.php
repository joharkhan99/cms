<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php

// if there is no token and email in URL
if (!isset($_GET['email']) && !isset($_GET['token'])) {
  redirect('index');
}

$query = "SELECT username, user_email, token FROM users WHERE token=?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
  mysqli_stmt_bind_param($stmt, "s", $_GET['token']);
  mysqli_stmt_execute($stmt);

  mysqli_stmt_bind_result($stmt, $username, $user_email, $token);

  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  //if token and email in url doesnt match to db email and token
  if ($_GET['token'] != $token || $_GET['email'] != $user_email) {
    redirect('index');
  }

  if (isset($_POST['password']) && isset($_POST['password-confirm'])) {
    if ($_POST['password'] == $_POST['password-confirm']) {

      // first hash new pass then update db using prped stmt
      $hashedPass = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 8]);
      $query = "UPDATE users SET token='', user_password='$hashedPass' WHERE user_email=?";

      $stmt = mysqli_prepare($connection, $query);
      if ($stmt) {

        mysqli_stmt_bind_param($stmt, "s", $_GET['email']);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) >= 1) {
          redirect("login.php");
        }

        mysqli_stmt_close($stmt);
      }
    }
  }
}


?>

<!-- navigation -->
<?php include "includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">

  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="text-center">

              <h2 class="text-center">Reset Password</h2>
              <p>You can reset your password here.</p>
              <div class="panel-body">

                <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                      <input id="password" name="password" placeholder="Enter new password" class="form-control" type="password" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-ok color-blue"></i></span>
                      <input id="password-confirm" name="password-confirm" placeholder="Confirm new password" class="form-control" type="password" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                  </div>

                  <input type="hidden" class="hide" name="token" id="token" value="">
                </form>

              </div><!-- Body-->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <?php include "includes/footer.php"; ?>

</div> <!-- /.container -->