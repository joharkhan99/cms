<?php include "../includes/db.php" ?>

<table class="table table-bordered table-hover">
  <thead>
    <th>Id</th>
    <th>Username</th>
    <th>Firstname</th>
    <th>Lastname</th>
    <th>E-mail</th>
    <th>Role</th>
    <th colspan="2" class="text-center">Change User Roles</th>
    <th>Edit</th>
    <th>Delete</th>
  </thead>
  <tbody>
    <tr>

      <?php
      $query = "SELECT * FROM users";
      $select_users = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_users)) {
        $user_id = escape($row['user_id']);
        $username = escape($row['username']);
        $user_password = escape($row['user_password']);
        $user_firstname = escape($row['user_firstname']);
        $user_lastname = escape($row['user_lastname']);
        $user_email = escape($row['user_email']);
        $user_role = escape($row['user_role']);
        $user_image = escape($row['user_image']);

        echo "
          <tr>
            <td>$user_id</td>
            <td>$username</td>
            <td>$user_firstname</td>
            <td>$user_lastname</td>
            <td>$user_email</td>
            <td>$user_role</td>
          <td><a href='users.php?change_to_admin={$user_id}'>Change to Admin</a></td>
          <td><a href='users.php?change_to_subscriber={$user_id}'>Change to Subscriber</a></td>
          <td><a href='users.php?source=edit_user&edit={$user_id}'>Edit</a></td>
          <td><a onClick=\"javascript: return confirm('Are you sure?');\" href='users.php?delete={$user_id}'>Delete</a></td>
        </tr>";
      }
      ?>
  </tbody>
</table>

<?php

// for changing user to admin
if (isset($_GET['change_to_admin'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_user_id = escape($_GET['change_to_admin']);
      $query = "UPDATE users SET user_role = 'Admin' WHERE user_id = $the_user_id";
      $change_user = mysqli_query($connection, $query);
      header("Location: users.php");      //reload page after delete
    }
  }
}

// for changing user to subscriber
if (isset($_GET['change_to_subscriber'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_user_id = escape($_GET['change_to_subscriber']);
      $query = "UPDATE users SET user_role = 'Subscriber' WHERE user_id = $the_user_id";
      $change_user = mysqli_query($connection, $query);
      header("Location: users.php");      //reload page after delete
    }
  }
}
// delete user(so that admins can do this and  aslo prevent SQL injection in URL)
if (isset($_GET['delete'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_user_id = escape($connection, $_GET['delete']);
      $query = "DELETE FROM users WHERE user_id = $the_user_id";
      $delete_user = mysqli_query($connection, $query);
      header("Location: users.php");      //reload page after delete
    }
  }
}

?>