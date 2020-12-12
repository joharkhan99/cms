<?php include("includes/header.php") ?>
<?php include("includes/db.php") ?>

<!-- Navigation -->
<?php include("includes/navigation.php") ?>

<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-8">

      <!-- <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1> -->

      <?php
      // show those posts only with post status as published
      $query = "SELECT * FROM posts WHERE post_status='published'";
      $select_all_posts = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_all_posts)) {
        $post_title = $row['post_title'];
        $post_id = $row['post_id'];
        $post_author = $row['post_author'];
        $post_date = $row['post_date'];
        $post_image = $row['post_image'];
        $post_content = $row['post_content'];
        $post_status = $row['post_status'];
        $post_views_count = $row['post_views_count'];
        // as content is too long so show less content on front
        if (strlen($post_content) > 100) {
          $post_content = substr($row['post_content'], 0, 160) . " ...";
        }

        //only published post displayed on home page
        if ($post_status == 'published') {

      ?>
          <!-- First Blog Post -->
          <h2>
            <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
          </h2>
          <p class="lead">
            by <a href="author_posts.php?author=<?php echo  $post_author ?>&p_id=<?php echo  $post_id ?>"><?php echo  $post_author ?></a>
          </p>
          <p style="display: inline; margin-right: 5px;">
            <span class="glyphicon glyphicon-time"></span> <?php echo  $post_date ?>
          </p>
          <p style="display: inline;">
            <span class="glyphicon glyphicon-eye-open"></span>
            <?php echo " " . $post_views_count; ?>
          </p>
          <hr>
          <a href="post.php?p_id=<?php echo $post_id ?>">
            <img class="img-responsive" width="50%" src="images/<?php echo $post_image ?>" alt="">
          </a>
          <hr>
          <p><?php echo  $post_content ?></p>
          <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

          <hr>
      <?php
        }
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