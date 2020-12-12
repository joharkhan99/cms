<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<!-- navigation -->
<?php include "includes/navigation.php"; ?>

<?php require "vendor/autoload.php";

// SETTING language vars
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
  $_SESSION['lang'] = $_GET['lang'];

  // refresh page if user changes lang 
  if (isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']) {
    echo "<script type='text/javascript'>location.reload();</script>";
  }
}

if (isset($_SESSION['lang'])) {
  include "includes/languages/" . $_SESSION['lang'] . ".php";
} else {
  include "includes/languages/en.php";
}

// PUSHER/NOTification stuff
$options = array(
  'cluster' => 'ap2',
  'encrypted' => true
);
// (app_id,key,secret,cluster)
$pusher = new Pusher\Pusher('196c42907c3d72316513', 'e7777d1ff8145668f096', '1112566', $options);

// AUTHENTICATION
// use server array instead of this isset($_POST['register'])
if ($_SERVER['REQUEST_METHOD'] == "POST") {   //this is better

  $email = trim($_POST['email']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $username = escape($_POST['username']);
  $email = escape($_POST['email']);
  $password = escape($_POST['password']);

  //___ we will store diff errors in this assoc array
  $error = [
    'username' => '',
    'email' => '',
    'password' => ''
  ];

  if (strlen($username) < 4) {
    $error['username'] = LONGUSERNAME;
  }

  if ($username == '') {
    $error['username'] = EMPTYUSERNAME;
  }

  if (username_exists($username)) {
    $error['username'] = USERNAMEEXIST;
  }

  if ($email == '') {
    $error['email'] = EMPTYEMAIL;
  }

  if (email_exists($email)) {
    $error['email'] = EMAILEXIST;
  }

  if (strlen($password) < 8) {
    $error['password'] = PASSLENGTH;
  }

  if ($password == '') {
    $error['password'] = PASSEMPTY;
  }


  foreach ($error as $key => $value) {
    if (empty($value)) {              //means no errors bcz value is empty

      //_____unset/clear all keys in array if there r no errors
      unset($error[$key]);
    }
  }

  //_____if error array is empty means no errors
  if (empty($error)) {
    register_user($username, $email, $password);

    $data['message'] = $username;
    $pusher->trigger('notifications', 'new_user', $data);

    login_user($email, $password);
  }
}

?>

<!-- content -->
<div class="container">

  <!-- for languages -->
  <form action="" class="navbar-form navbar-right" id="language-form" method="GET">
    <div class="form-group">
      <select name="lang" class="form-control" onchange="changeLang()">
        <option value="" selected>Change language</option>
        <option value="en" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') {
                              echo "selected";
                            } ?>>English</option>
        <option value="urdu" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'urdu') {
                                echo "selected";
                              } ?>>Urdu</option>
      </select>
    </div>
  </form>
  <!-- // -->

  <section id="login">
    <div class="container">
      <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
          <div class="form-wrap">
            <h1><?php echo REGISTER; ?></h1>

            <!-- form -->
            <form action="registration.php" id="login-form" method="POST" autocomplete="off" role="form">

              <div class="form-group">
                <label for="username" class="sr-only">Username</label>
                <input type="text" class="form-control" name="username" placeholder="<?php echo USERNAME; ?>" id="username" value="<?php echo isset($username) ? $username : '' ?>">

                <small style="color: red;">
                  <!-- if any error occured in username -->
                  <?php echo isset($error['username']) ? $error['username'] : '' ?>
                </small>

              </div>

              <div class="form-group">
                <label for="email" class="sr-only">Email</label>
                <input type="email" class="form-control" name="email" placeholder="<?php echo EMAIL; ?>" id="email" value="<?php echo isset($email) ? $email : '' ?>">

                <small style="color: red;">
                  <!-- if any error occured in email -->
                  <?php echo isset($error['email']) ? $error['email'] : '' ?>
                </small>

              </div>

              <div class="form-group">
                <label for="password" class="sr-only">Password</label>
                <input type="password" class="form-control" name="password" placeholder="<?php echo PASSWORD; ?>" id="key">

                <small style="color: red;">
                  <!-- if any error occured in password -->
                  <?php echo isset($error['password']) ? $error['password'] : '' ?>
                </small>

              </div>

              <input type="submit" id="btn-login" class="btn btn-primary btn-lg btn-block" value="<?php echo REGISTER; ?>" name="register">
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script>
  function changeLang() {
    document.getElementById('language-form').submit();
  }
</script>


<?php include "includes/footer.php"; ?>