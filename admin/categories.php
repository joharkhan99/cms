<?php include "includes/admin_header.php" ?>

<div id="wrapper">
  <!-- Navigation -->
  <?php include "includes/admin_navigation.php" ?>

  <div id="page-wrapper">
    <div class="container-fluid">

      <!-- Page Heading -->
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            Welcome To Admin
            <small>Author</small>
          </h1>

          <div class="col-xs-6">
            <!-- insert / add catg -->
            <?php insert_categories(); ?>

            <!-- add catg form -->
            <form action="" method="POST">
              <div class="form-group">
                <label for="cat_title">New Category</label>
                <input class="form-control" type="text" name="cat_title">
              </div>
              <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
              </div>
            </form>
            <!-- add catg form ends -->

            <!-- update and include category -->
            <?php
            if (isset($_GET['edit'])) {
              include "includes/update_categories.php";
            }
            ?>
          </div>

          <div class="col-xs-6">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Category Title</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                <!-- find and bring all categories -->
                <?php findAllCategories(); ?>

                <!-- Delete category from Dbase -->
                <?php delete_categories(); ?>

              </tbody>
            </table>
          </div>

        </div>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

  </div>
  <!-- /#page-wrapper -->
  <?php include "includes/admin_footer.php" ?>