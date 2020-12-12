<?php
//________insert new category to DB
function insert_categories()
{
  global $connection;

  if (isset($_POST['submit'])) {

    $cat_title = escape($_POST['cat_title']);

    if ($cat_title == "" || empty($cat_title)) {

      echo "<i style='color: tomato'>please fill the field</i>";
    } else {

      $query = "INSERT INTO categories(catg_title, user_id) VALUE(?,?)";
      // 1. prepare statement
      $stmt = mysqli_prepare($connection, $query);
      // 2. insert/append/bind data t atatement/query
      $user_id = loggedInUserId();
      mysqli_stmt_bind_param($stmt, "si", $cat_title, $user_id);
      // 3. execute the statement
      mysqli_stmt_execute($stmt);

      confirmQuery($stmt);
    }

    // 4. last step -> PHP do this it self but close prepared statement for effiency
    mysqli_stmt_close($stmt);
  }
}

//________find and bring all categories
function findAllCategories()
{
  global $connection;

  $query = "SELECT * FROM categories";
  $select_all_categories = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($select_all_categories)) {
    $catg_id = escape($row['catg_id']);
    $catg_title = escape($row['catg_title']);
    echo "
    <tr>
      <td>$catg_id</td>
      <td>$catg_title</td>
      <td><a href='categories.php?edit={$catg_id}'>Edit</a></td>
      <td><a onClick=\"javascript: return confirm('Are you sure?');\" href='categories.php?delete={$catg_id}'>Delete</a></td>
    </tr>";
  }
}

//________insert new category to DB
function delete_categories()
{
  global $connection;

  if (isset($_GET['delete'])) {
    $cat_id_to_delete = escape($_GET['delete']);
    $query = "DELETE FROM categories WHERE catg_id = {$cat_id_to_delete}";
    mysqli_query($connection, $query);

    //reload page after deleting category
    header("Location: categories.php");
  }
}

//_______checking total online users
function count_Online_Users()
{

  if (isset($_GET['onlineusers'])) {

    global $connection;

    // if connection var is not working thne  include db.php
    if (!$connection) {
      session_start();
      include "../includes/db.php";

      $session = session_id();  //session id of every user visiting this site
      $time = time();
      $time_out_in_sec = 5;          //time when user leaves site
      $time_out = $time - $time_out_in_sec;   //subtract time to update time

      $query = "SELECT * FROM users_online WHERE session = '$session'";
      $send_query = mysqli_query($connection, $query);
      $count = mysqli_num_rows($send_query);

      //means no user exists at this time so insert new users
      if ($count == NULL) {
        mysqli_query($connection, "INSERT INTO users_online(session,time) VALUES('$session', '$time')");
      }
      //means there are users online so update and add users and sessions
      else {
        mysqli_query($connection, "UPDATE users_online SET time='$time' WHERE session='$session'");
      }

      //for counting total users online
      $user_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out' ");
      $count_users = mysqli_num_rows($user_online_query);

      echo $count_users;
    }
  } //get request isset
}

//call above function to work properly
count_Online_Users();


///////////////////////////////////////////////
//________Refactoring index.php
function recordCount($table)
{
  global $connection;
  $query = "SELECT * FROM $table";
  $get_all_posts = mysqli_query($connection, $query);
  $count = mysqli_num_rows($get_all_posts);

  return $count;
}

function checkStatus($table, $column, $status)
{
  global $connection;
  $query = "SELECT * FROM $table WHERE $column = '$status' ";
  $get_all_posts = mysqli_query($connection, $query);
  $count = mysqli_num_rows($get_all_posts);

  return $count;
}

//______func to redirect user to somewhere else
function redirect($location)
{
  header("Location: " . $location);
  exit;
}

//_____user specific posts
function getAllUserPosts()
{
  global $connection;
  $query = "SELECT * FROM posts WHERE user_id=" . $_SESSION['user_id'] . "";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//_____user specific comments
function getAllUserComments()
{
  global $connection;
  $query = "SELECT * FROM comments WHERE comment_email='" . $_SESSION['email'] . "'";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//_____user specific approved comments
function getAllUserApprovedComments()
{
  global $connection;
  $query = "SELECT * FROM comments WHERE comment_email='" . $_SESSION['email'] . "' AND comment_status='approved'";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//_____user specific Unapproved comments
function getAllUserUnapprovedComments()
{
  global $connection;
  $query = "SELECT * FROM comments WHERE comment_email='" . $_SESSION['email'] . "' AND comment_status='unapproved'";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//_____user specific added categories
function getAllUserCategories()
{
  global $connection;
  $query = "SELECT * FROM categories WHERE user_id='" . $_SESSION['user_id'] . "'";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//_____user specific publish posts
function getAllUserPublishPosts()
{
  global $connection;
  $query = "SELECT * FROM posts WHERE user_id='" . $_SESSION['user_id'] . "' AND post_status='published'";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//_____user specific draft posts
function getAllUserDraftPosts()
{
  global $connection;
  $query = "SELECT * FROM posts WHERE user_id='" . $_SESSION['user_id'] . "' AND post_status='draft'";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//______func to check post,get...and other request mthds
function ifIsMethod($method = null)
{
  if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
    return true;
  }
  return false;
}

//______to check if logged in
function isLoggedIn()
{
  if (isset($_SESSION['user_role'])) {      //either admin/subscriber
    return true;
  }
  return false;
}

//______to get id of logged in user
function loggedInUserId()
{
  global $connection;
  if (isLoggedIn()) {
    $query = "SELECT * FROM users WHERE user_email='" . $_SESSION['email'] . "'";
    $result = mysqli_query($connection, $query);
    $users = mysqli_fetch_assoc($result);

    return mysqli_num_rows($result) >= 1 ? $users['user_id'] : false;
  }
  return false;
}

//______to cgeck if user liked post
function userLikedThisPost($post_id = '')
{
  global $connection;
  $query = "SELECT * FROM likes WHERE user_id=" . loggedInUserId() . " AND post_id=$post_id";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result) >= 1 ? true : false;
}

//______count total likes on post
function countPostLikes($post_id)
{
  global $connection;
  $query = "SELECT * FROM likes WHERE post_id=$post_id";
  $result = mysqli_query($connection, $query);
  return mysqli_num_rows($result);
}

//______check if logged in and then redirect
function checkIfLoggedInAndRedirect($redirectLoc = null)
{
  if (isLoggedIn()) {
    redirect($redirectLoc);
  }
}

//_______function against SQL injection by hackers
function escape($string)
{
  global $connection;
  return mysqli_real_escape_string($connection, trim($string));
}

//________confirm query passed/fialed
function confirmQuery($result)
{
  global $connection;
  if (!$result) {
    die("Query Failed...: " . mysqli_error($connection));
  }
}

//_______admin checking
function isAdmin($username = '')
{
  global $connection;
  if (isLoggedIn()) {
    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);

    confirmQuery($result);

    $row = mysqli_fetch_assoc($result);
    if ($row['user_role'] == 'Admin')
      return true;
    else
      return false;
  }
}

//________check duplicate username
function username_exists($username)
{
  global $connection;
  $query = "SELECT username FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $query);
  confirmQuery($result);

  if (mysqli_num_rows($result) > 0)     //means there is user with given username
    return true;
  else
    return false;
}

//________check duplicate emails
function email_exists($email)
{
  global $connection;
  $query = "SELECT user_email FROM users WHERE user_email = '$email'";
  $result = mysqli_query($connection, $query);
  confirmQuery($result);

  if (mysqli_num_rows($result) > 0)     //means there is a user with given email
    return true;
  else
    return false;
}

//________register user function
function register_user($username, $email, $password)
{
  global $connection;

  $username = mysqli_real_escape_string($connection, $username);
  $email = mysqli_real_escape_string($connection, $email);
  $password = mysqli_real_escape_string($connection, $password);

  // better way than below two (cost is amount of iterations)
  $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 8]);

  // $query = "SELECT randSalt FROM users";
  // $get_rand_salt_query = mysqli_query($connection, $query);

  // if (!$get_rand_salt_query) {
  //   die("Query Failed" . mysqli_error($connection));
  // }

  // below is default salt in db and will be same for all users
  // $row = mysqli_fetch_assoc($get_rand_salt_query);
  // $salt = $row['randSalt'];

  // lets create new salt everytime new user registers (strong passwords)
  // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./';
  // $salt = '$2y$14$';
  // for ($i = 0; $i < 22; $i++) {
  //   $index = rand(0, strlen($characters) - 1);    //generate rand number
  //   $salt .= $characters[$index];                 //concat salt and num
  // }

  // $password = crypt($password, $salt);

  $query = "INSERT INTO users(user_role, username, user_email, user_password) VALUES('Subscriber','$username','$email','$password')";

  $reg_user_query = mysqli_query($connection, $query);
  confirmQuery($reg_user_query);
}


//________Login users
function login_user($email, $password)
{
  global $connection;

  $email = trim($email);
  $password = trim($password);

  $email = mysqli_real_escape_string($connection, $email);
  $password = mysqli_real_escape_string($connection, $password);

  $query = "SELECT * FROM users WHERE user_email = '{$email}'";
  $select_user_query = mysqli_query($connection, $query);
  confirmQuery($select_user_query);

  if (!$select_user_query) {
    die('QUERY FAILED.' . mysqli_error($connection));
  }

  while ($row = mysqli_fetch_assoc($select_user_query)) {
    $db_id = escape($row['user_id']);
    $db_username = escape($row['username']);
    $db_firstname = escape($row['user_firstname']);
    $db_lastname = escape($row['user_lastname']);
    $db_role = escape($row['user_role']);
    $db_password = escape($row['user_password']);
    $db_email = escape($row['user_email']);

    // PASSWORD DECRYPTION
    //db_pass contains the hashed version and pass contains user login
    // $password = crypt($password, $db_password);

    //new way of password decrypt (returns true or false)
    $password = password_verify($password, $db_password);

    if ($email === $db_email && $password) {
      $_SESSION["user_id"] = $db_id;
      $_SESSION["email"] = $db_email;
      $_SESSION["username"] = $db_username;
      $_SESSION["firstname"] = $db_firstname;
      $_SESSION["lastname"] = $db_lastname;
      $_SESSION["user_role"] = $db_role;

      redirect("admin");
    } else {

      return false;         // means login info is incorrect
    }
  }
}

//______function to check if there is a user
function currentUser()
{
  if (isset($_SESSION['username'])) {
    return $_SESSION['username'];
  } else {
    return false;
  }
}

//______for default image if user dont provide any image
function imagePlaceholder($image = "")
{
  if (!$image)
    return "ripple.gif";
  else
    return $image;
}
