<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">CMS</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

        <?php
        $query = "SELECT * FROM categories LIMIT 3";
        $select_all_category = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_all_category)) {
          $cat_title = escape($row['catg_title']);
          $cat_id = escape($row['catg_id']);

          $category_class = '';
          $registration_class = '';

          // The basename() function returns the filename from a path/url.
          // $_SERVER['PHP_SELF'] returns name and path of current file (from the root folder) like (category.php,index.php...)
          $pageName = basename($_SERVER['PHP_SELF']);
          $registration_page = 'registration.php';

          if (isset($_GET['category']) && $_GET['category'] == $cat_id) {
            $category_class = 'active';
          } elseif ($pageName == $registration_page) {
            $registration_class = 'active';
          }

          echo "<li class='$category_class'><a href='category.php?category={$cat_id}'>{$cat_title}</a></li>";
        }
        ?>

        <!-- below for showing links in navbar for login/not loggin -->

        <!-- start if -->
        <?php if (isLoggedIn()) : ?>

          <li>
            <a href="admin">Admin</a>
          </li>
          <li>
            <a href="includes/logout.php">Log out</a>
          </li>

        <?php else : ?>

          <li>
            <a href="login.php">Login</a>
          </li>

        <?php endif; ?>
        <!-- ./end if statement -->

        <li class="<?php echo $registration_class ?>">
          <a href="registration.php">Register</a>
        </li>

        <?php
        if (isset($_SESSION['user_role'])) {
          if (isset($_GET['p_id'])) {
            $post_id = escape($_GET['p_id']);
            echo "<li><a href='admin/posts.php?source=edit_post&p_id={$post_id}'>Edit Post</a></li>";
          }
        }
        ?>
        <!-- 
        <li>
          <a href="#">Contact</a>
        </li> -->


      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </div>
  <!-- /.container -->
</nav>