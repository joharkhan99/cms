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

      if (isset($_GET['submit'])) {
        $search = escape($_GET['search']);

        // Finds any values that have above search value in any of tags
        $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%'";
        $search_query = mysqli_query($connection, $query);
        if (!$search_query) {
          die('query failed' . mysqli_error($connection));
        }
        $count = mysqli_num_rows($search_query);
        if ($count == 0) {
          echo '<h3>Not Found :(</h3>';
        } else {
          // show only the found ones-> ones with same tag name as search
          while ($row = mysqli_fetch_assoc($search_query)) {
            $post_title = escape($row['post_title']);
            $post_author = escape($row['post_author']);
            $post_date = escape($row['post_date']);
            $post_image = escape($row['post_image']);
            $post_content = escape($row['post_content']);
      ?>
            <!-- First Blog Post -->
            <h2>
              <a href="#"><?php echo $post_title ?></a>
            </h2>
            <p class="lead">
              by <a href="index.php"><?php echo  $post_author ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo  $post_date ?></p>
            <hr>
            <img class="img-responsive" src="images/<?php echo $post_image ?>.jpg" alt="">
            <hr>
            <p><?php echo  $post_content ?></p>
            <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>
      <?php
          }
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