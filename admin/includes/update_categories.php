<!-- edit form sarts -->
<form action="" method="POST">
  <div class="form-group">
    <label for="cat_title">Edit Category</label>
    <?php
    if (isset($_GET['edit'])) {
      $catgId = escape($_GET['edit']);

      $query = "SELECT * FROM categories WHERE catg_id = $catgId";
      $select_categories_id = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_categories_id)) {
        $catg_id = escape($row['catg_id']);
        $catg_title = escape($row['catg_title']);
    ?>
        <input value="<?php if (isset($catg_title)) {
                        echo $catg_title;
                      } ?>" class="form-control" type="text" name="cat_title">
    <?php  }
    } ?>

    <?php
    // update query sent to database
    if (isset($_POST['update'])) {

      $cat_title_toUpdt = escape($_POST['cat_title']);

      if ($cat_title_toUpdt == "" || empty($cat_title_toUpdt)) {

        echo "<i class='text-danger'>*empty field</i>";
      } else {
        $query = "UPDATE categories SET catg_title = ? WHERE catg_id = ?";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "si", $cat_title_toUpdt, $catg_id);
        mysqli_stmt_execute($stmt);

        confirmQuery($stmt);

        // last step -> PHP do this it self but close prepared statement for effiency
        mysqli_stmt_close($stmt);

        header("Location: categories.php");
      }
    }
    ?>

  </div>
  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="update" value="Update Category">
  </div>
</form>
<!-- edit form ends -->