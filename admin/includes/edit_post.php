<?php

//when user clicks on edit button then write data of that post in form
if (isset($_GET['p_id'])) {
  $the_post_id = escape($_GET['p_id']);
}

$query = "SELECT * FROM posts WHERE post_id = {$the_post_id}";
$select_posts_by_id = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($select_posts_by_id)) {
  $post_id = escape($row['post_id']);
  $post_user = escape($row['post_user']);
  $post_title = escape($row['post_title']);
  $post_category_id = escape($row['post_category_id']);
  $post_status = escape($row['post_status']);
  $post_image = escape($row['post_image']);
  $post_content = escape($row['post_content']);
  $post_tags = escape($row['post_tags']);
  $post_date = escape($row['post_date']);
  $post_views_count = escape($row['post_views_count']);
}

//extract all fields from forms and update Database
if (isset($_POST['update_post'])) {
  $post_user = escape($_POST['post_user']);
  $post_title = escape($_POST['post_title']);
  $post_category_id = escape($_POST['post_category']);
  $post_status = escape($_POST['post_status']);
  $post_image = $_FILES['post_image']['name'];
  $post_image_tempAddress = $_FILES['post_image']['tmp_name'];
  $post_tags = escape($_POST['post_tags']);
  $post_content = escape($_POST['post_content']);
  $post_views_count_option = escape($_POST['post_views_count']);

  // for image
  move_uploaded_file($post_image_tempAddress, "../images/$post_image");
  if (empty($post_image)) {
    $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
    $select_image = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($select_image)) {
      $post_image = $row['post_image'];
    }
  }

  // for views reset count
  if ($post_views_count_option == 'yes') {
    $post_views_count = 0;
  }

  $query = "UPDATE posts SET post_title = '$post_title', post_category_id = '$post_category_id', post_date = now(), post_user = '$post_user', post_status = '$post_status', post_image = '$post_image', post_tags = '$post_tags', post_content = '$post_content', post_views_count=$post_views_count WHERE post_id = $the_post_id";

  $update_post = mysqli_query($connection, $query);

  confirmQuery($update_post);

  echo "post updated " . "<p class='bg-success'><a href='../post.php?p_id=$post_id'>View Post</a> " . " or <a href='posts.php'>Edit More Post</a> </p>";
}

?>

<form action="" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label>Post Title</label>
    <input value="<?php echo $post_title ?>" type="text" class="form-control" name="post_title">
  </div>

  <div class="form-group">
    <label>Category</label>
    <select name="post_category" id="" class="form-control w-25">

      <!-- show all categories -->
      <?php
      $query = "SELECT * FROM categories";
      $select_categories = mysqli_query($connection, $query);

      // confirmQuery($select_categories);

      while ($row = mysqli_fetch_assoc($select_categories)) {
        $catg_id = escape($row['catg_id']);
        $catg_title = escape($row['catg_title']);

        if ($catg_id == $post_category_id) {
          echo "<option value='$catg_id' selected>$catg_title</option>";
        } else {
          echo "<option value='$catg_id'>$catg_title</option>";
        }
      }
      ?>

    </select>
  </div>


  <div class="form-group">
    <label>Users</label>
    <select name="post_user" id="" class="form-control w-25">
      <?php
      echo "<option value='$post_user'>$post_user</option>";
      ?>
      <!-- show all users from users table in db-->
      <?php
      $query = "SELECT * FROM users";
      $select_users = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_users)) {
        $user_id = escape($row['user_id']);
        $username = escape($row['username']);

        echo "<option value='$username'>$username</option>";
      }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label>Post Status</label>
    <select name="post_status" id="" class="form-control w-25">
      <option value='<?php echo $post_status; ?>'><?php echo $post_status; ?></option>
      <?php
      if ($post_status == "published") {
        echo "<option value='draft'>draft</option>";
      } else {
        echo "<option value='published'>published</option>";
      }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label>Post Image</label>
    <img src="../images/<?php echo $post_image; ?>" width="100px" alt="">
    <input type="file" class="form-control" name="post_image">
  </div>

  <div class="form-group">
    <label>Post Tags</label>
    <input value="<?php echo $post_tags ?>" type="text" class="form-control" name="post_tags">
  </div>

  <div class="form-group">
    <label>Reset Post Views Count</label>
    <select name="post_views_count" class="form-control">
      <option value="" selected>Select an option</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>

  <div class="form-group">
    <label>Post Content</label>
    <textarea name="post_content" id="editor" class="form-control" cols="30" rows="10"><?php echo $post_content ?></textarea>
  </div>

  <div class="form-group">
    <input type="submit" value="Update Post" class="btn btn-primary" name="update_post">
  </div>

</form>