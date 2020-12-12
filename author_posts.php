<?php include("includes/header.php") ?>
<?php include("includes/db.php") ?>


<!-- Navigation -->
<?php include("includes/navigation.php") ?>

<!-- Page Content -->
<div class="container">

  <h1>Posts By <?php echo $_GET['author'] ?></h1>
  <hr>

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-8">

      <?php

      if (isset($_GET['p_id'])) {
        $the_post_id = escape($_GET['p_id']);
        $post_author = escape($_GET['author']);
      }

      $query = "SELECT * FROM posts WHERE post_user = '$post_author'";
      $select_all_posts = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_all_posts)) {
        $post_title = escape($row['post_title']);
        $post_author = escape($row['post_user']);
        $post_date = escape($row['post_date']);
        $post_image = escape($row['post_image']);
        $post_content = escape($row['post_content']);
      ?>
        <!-- First Blog Post -->
        <a href="post.php?p_id=<?php echo $the_post_id; ?>">
          <h2>
            <?php echo $post_title ?>
          </h2>
        </a>
        <p class="lead">
          post by <?php echo  $post_author ?>
        </p>
        <p><span class="glyphicon glyphicon-time"></span> <?php echo  $post_date ?></p>
        <hr>
        <img class="img-responsive" width="50%" src="images/<?php echo $post_image ?>" alt="">
        <hr>
        <p><?php echo  $post_content ?></p>
        <hr>
      <?php
      }
      ?>

      <!-- Blog Comments -->

      <?php
      if (isset($_POST['create_comment'])) {
        $the_post_id = escape($_GET['p_id']);

        $comment_author = escape($_POST['comment_author']);
        $comment_email = escape($_POST['comment_email']);
        $comment_content = escape($_POST['comment_content']);

        if ($comment_author == '' || $comment_email == '' || $comment_content == '') {
          echo "<i class='text-danger'>fill all fields</i>";
        } else {
          $query = "INSERT INTO comments(comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) VALUES ($the_post_id, '$comment_author', '$comment_email', '$comment_content', 'unapproved', now())";

          $create_comment_query = mysqli_query($connection, $query);

          confirmQuery($create_comment_query);

          //increase comment counts for post
          $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = $the_post_id";
          $update_comment_count = mysqli_query($connection, $query);

          confirmQuery($update_comment_count);
        }
      }
      ?>

    </div>

    <!-- Blog Sidebar Widgets Column -->
    <?php include("includes/sidebar.php") ?>

  </div>
  <!-- /.row -->

  <hr>

  <?php include("includes/footer.php") ?>