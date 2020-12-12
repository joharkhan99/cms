<?php include("includes/header.php") ?>
<?php include("includes/db.php") ?>

<!-- Navigation -->
<?php include("includes/navigation.php") ?>

<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-8">

      <?php
      if (isset($_GET['category'])) {
        $post_category_id = escape($_GET['category']);

        if (isAdmin($_SESSION['username'])) {
          // '?' in query is for prepared statement in PHP

          $query = "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ?";

          // prepare statement to database
          $stmt1 = mysqli_prepare($connection, $query);
        } else {
          $query = "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = ?";

          $stmt2 = mysqli_prepare($connection, $query);
          $published = 'published';
        }

        if (isset($stmt1)) {
          // "i" for integer
          // 1. bind/put data into statement on "?" place in statement
          mysqli_stmt_bind_param($stmt1, "i", $post_category_id);
          // 2. execute statement
          mysqli_stmt_execute($stmt1);
          // 3. fetch data as we specified in query and assigned it to these vars
          mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);

          $stmt = $stmt1;
        } else {
          // "i" for integer and "s" for string
          mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published);
          mysqli_stmt_execute($stmt2);
          mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);

          $stmt = $stmt2;
        }

        // $select_all_posts = mysqli_query($connection, $query);

        while (mysqli_stmt_fetch($stmt)) {
          // as content is too long so show less content on front
          if (strlen($post_content) > 100) {
            $post_content = substr($row['post_content'], 0, 160) . " ...";
          }
      ?>
          <!-- First Blog Post -->
          <h2>
            <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
          </h2>
          <p class="lead">
            by <a href="index.php"><?php echo  $post_author ?></a>
          </p>
          <p><span class="glyphicon glyphicon-time"></span> <?php echo  $post_date ?></p>
          <hr>
          <img class="img-responsive" width="50%" src="images/<?php echo $post_image ?>" alt="">
          <hr>
          <p><?php echo  $post_content ?></p>
          <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

          <hr>
      <?php
        }
        // 4. PHP do this it self but close prepared statement for effiency
        mysqli_stmt_close($stmt);
      } else {
        header("Location: index.php");
      }
      ?>

      <!-- Pager -->
      <ul class="pager">
        <li class="previous">
          <a href="#">&larr; Older</a>
        </li>
        <li class="next">
          <a href="#">Newer &rarr;</a>
        </li>
      </ul>

    </div>

    <!-- Blog Sidebar Widgets Column -->
    <?php include("includes/sidebar.php") ?>

  </div>
  <!-- /.row -->

  <hr>

  <?php include("includes/footer.php") ?>