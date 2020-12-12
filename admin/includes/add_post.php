<?php

echo "<h1>" . loggedInUserId() . "</h1>";
if (isset($_POST['create_post'])) {
  $post_title = escape($_POST['post_title']);
  $post_category_id = escape($_POST['post_category']);
  $post_user = escape($_POST['post_user']);
  $post_status = escape($_POST['post_status']);

  $post_image = $_FILES['post_image']['name'];
  $post_image_tempAddress = $_FILES['post_image']['tmp_name'];

  $post_tags = escape($_POST['post_tags']);
  $post_content = escape($_POST['post_content']);
  $post_date = date('d-m-y');
  $post_user_id = loggedInUserId();

  //move img from temp addrss to images folder with its name
  move_uploaded_file($post_image_tempAddress, "../images/$post_image");

  $query = "INSERT INTO posts(post_title,post_category_id,post_user,post_status,post_image,post_tags,post_content,post_date,user_id) VALUES('$post_title','$post_category_id','$post_user','$post_status','$post_image','$post_tags','$post_content',now(),'$post_user_id')";

  $create_post_query = mysqli_query($connection, $query);

  confirmQuery($create_post_query);

  //This function returns the id (generated with AUTO_INCREMENT) from the last query. will grab the last created id which will be a new post
  $post_id = mysqli_insert_id($connection);

  echo "post updated " . "<p class='bg-success'><a href='../post.php?p_id=$post_id'>View Post</a> " . " or <a href='posts.php'>Edit More Post</a> </p>";
}
?>


<form action="" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label>Post Title</label>
    <input type="text" class="form-control" name="post_title">
  </div>
  <div class="form-group">
    <label>Category Title</label>
    <select name="post_category" id="" class="form-control w-25">
      <!-- show all categories from categories table in db-->
      <?php
      $query = "SELECT * FROM categories";
      $select_categories = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_categories)) {
        $catg_id = escape($row['catg_id']);
        $catg_title = escape($row['catg_title']);

        echo "<option value='$catg_id'>$catg_title</option>";
      }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label>Users</label>
    <select name="post_user" id="" class="form-control w-25">
      <option value="" selected>Select an Option</option>
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
    <select name="post_status" class="form-control">
      <option value='' selected>Select Option</option>
      <option value='published'>Publish</option>
      <option value='draft'>Draft</option>
      <option value='delete'>Delete</option>
    </select>
  </div>
  <div class="form-group">
    <label>Post Image</label>
    <input type="file" class="form-control" name="post_image">
  </div>
  <div class="form-group">
    <label>Post Tags</label>
    <input type="text" class="form-control" name="post_tags">
  </div>
  <div class="form-group">
    <label>Post Content</label>
    <textarea name="post_content" id="editor" class="form-control" cols="30" rows="10"></textarea>
  </div>
  <div class="form-group">
    <input type="submit" value="Publish Post" class="btn btn-primary" name="create_post">
  </div>

</form>