<?php
if (ifIsMethod('post')) {
  if (isset($_POST['email']) && isset($_POST['password'])) {
    login_user($_POST['email'], $_POST['password']);
  } else {
    redirect("index");
  }
}

?>


<!-- Blog Sidebar Widgets Column -->
<div class="col-md-4">

  <!-- Blog Search Well -->
  <div class="well">
    <h4>Blog Search</h4>
    <!--search form-->
    <form action="search.php" method="GET">
      <div class="input-group">
        <input name="search" type="text" class="form-control" placeholder="search any tags">
        <span class="input-group-btn">
          <button name="submit" class="btn btn-default" type="submit">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
      </div>
    </form>
    <!-- /.input-group -->
  </div>

  <!--Login -->
  <div class="well">

    <?php if (isset($_SESSION['user_role'])) : ?>

      <h4>Logged in as <?php echo $_SESSION['username'] ?></h4>
      <a href="includes/logout.php" class="btn btn-primary">Logout</a>

    <?php else : ?>

      <h4>Login</h4>
      <form method="post">
        <div class="form-group">
          <input name="email" type="text" class="form-control" placeholder="Enter Email">
        </div>
        <div class="input-group">
          <input name="password" type="password" class="form-control" placeholder="Enter Password">
          <span class="input-group-btn">
            <button class="btn btn-primary" name="login" type="submit">Submit
            </button>
          </span>
        </div>
        <div class="form-group">
          <!-- uniqid(built-in) produces unique id -->
          <a href="forgot-password.php?forgot=<?php echo uniqid(true); ?>">Forgot Password</a>
        </div>
      </form>

    <?php endif; ?>

  </div>

  <!-- Blog Categories Well -->
  <div class="well">

    <?php
    $query = "SELECT * FROM categories";
    $select_categories_sidebar = mysqli_query($connection, $query);
    ?>

    <h4>Blog Categories</h4>
    <div class="row">
      <div class="col-lg-12">
        <ul class="list-unstyled">

          <?php
          while ($row = mysqli_fetch_assoc($select_categories_sidebar)) {
            $cat_title = escape($row['catg_title']);
            $cat_id = escape($row['catg_id']);
            echo "<li><a href='category.php?category=$cat_id'>{$cat_title}</a></li>";
          }
          ?>
        </ul>
      </div>
    </div>
    <!-- /.row -->
  </div>

  <!-- Side Widget Well -->
  <?php include "widget.php" ?>

</div>