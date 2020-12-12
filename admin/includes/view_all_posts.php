<?php

//boostrap modal
include "delete_modal.php";

// this array contains ids of selected posts
if (isset($_POST['checkBoxArray'])) {

  foreach ($_POST['checkBoxArray'] as $checkBoxValue) {
    $bulk_options = escape($_POST['bulk_options']);     //draft,published,delete

    switch ($bulk_options) {
      case 'published':
        $query = "UPDATE posts SET post_status = '$bulk_options' WHERE post_id = $checkBoxValue";
        $publish_post = mysqli_query($connection, $query);
        header("Location: posts.php");
        break;

      case 'draft':
        $query = "UPDATE posts SET post_status = '$bulk_options' WHERE post_id = $checkBoxValue";
        $draft_post = mysqli_query($connection, $query);
        header("Location: posts.php");
        break;

      case 'delete':
        $query = "DELETE FROM posts WHERE post_id = $checkBoxValue";
        $delete_post = mysqli_query($connection, $query);
        header("Location: posts.php");
        break;

      case 'clone':
        $query = "SELECT * FROM posts WHERE post_id = $checkBoxValue";
        $select_post = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_post)) {
          $post_title = escape($row['post_title']);
          $post_category_id = escape($row['post_category_id']);
          $post_user = escape($row['post_user']);
          $post_status = escape($row['post_status']);
          $post_image = escape($row['post_image']);
          $post_content = escape($row['post_content']);
          $post_tags = escape($row['post_tags']);
          $post_date = escape($row['post_date']);
        }
        $query = "INSERT INTO posts(post_title,post_category_id,post_user,post_status,post_image,post_tags,post_content,post_date) VALUES('$post_title','$post_category_id','$post_user','$post_status','$post_image','$post_tags','$post_content',now())";

        $clone_post_query = mysqli_query($connection, $query);

        header("Location: posts.php");
        break;

      case 'reset_views':
        $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = $checkBoxValue";
        $reset_post_views = mysqli_query($connection, $query);

        header("Location: posts.php");
        break;

      default:
        # code...
        break;
    }
  }
}

?>

<form action="" method="POST">

  <table class="table table-bordered table-hover">

    <div id="bulkOptionsContainer" class="col-xs-4" style="padding-left: 0;padding-bottom: 10px;">
      <select name="bulk_options" id="" class="form-control">
        <option value="" selected>Select Option</option>
        <option value="published">Publish</option>
        <option value="draft">Draft</option>
        <option value="clone">Clone</option>
        <option value="reset_views">Reset Post Views</option>
        <option value="delete">Delete</option>
      </select>
    </div>

    <div class="col-xs-6">
      <input type="submit" name="submit" class="btn btn-success" value="Apply">
      <a href="posts.php?source=add_post" class="btn btn-primary">Add New</a>
    </div>

    <thead>
      <th><input title="select all" type="checkbox" name="" id="selectAllBoxes"></th>
      <th>Id</th>
      <th>Users</th>
      <th>Title</th>
      <th>Category</th>
      <th>Status</th>
      <th>Image</th>
      <th>Tags</th>
      <th>Comments</th>
      <th>Date</th>
      <th>View</th>
      <th>Edit</th>
      <th>Delete</th>
      <th>Post Views</th>
    </thead>
    <tbody>
      <tr>

        <?php
        // $query = "SELECT * FROM posts ORDER BY post_id DESC";

        //join categories/posts table so that v dont have todo multiple queries
        $query = "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.catg_id, categories.catg_title FROM posts LEFT JOIN categories ON categories.catg_id=posts.post_category_id ORDER BY posts.post_id DESC";

        $select_posts = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_posts)) {
          $post_id = escape($row['post_id']);
          $post_author = escape($row['post_author']);
          $post_user = escape($row['post_user']);
          $post_title = escape($row['post_title']);
          $post_category_id = escape($row['post_category_id']);
          $post_comment_count = escape($row['post_comment_count']);
          $post_status = escape($row['post_status']);
          $post_image = escape($row['post_image']);
          $post_tags = escape($row['post_tags']);
          $post_date = escape($row['post_date']);
          $post_views_count = escape($row['post_views_count']);
          $catg_title = escape($row['catg_title']);
          $catg_id = escape($row['catg_id']);

          $post_image = imagePlaceholder($post_image);


          echo "
          <tr>";
        ?>
          <!-- when a post box is checked store its id in array -->
          <td><input type='checkbox' class='checkBoxes' name="checkBoxArray[]" value="<?php echo $post_id; ?>"></td>

          <?php
          echo "<td>$post_id</td>";

          if (!empty($post_author)) {
            echo "<td>$post_author</td>";
          } else if (!empty($post_user)) {
            echo "<td>$post_user</td>";
          }

          echo "<td>$post_title</td>
          <td>$catg_title</td>
          <td>$post_status</td>
          <td><img src='../images/$post_image' alt='image' width='100px'></td>
          <td>$post_tags</td>";
          imagePlaceholder($post_image);
          //count total comment rows for a specific post by compre ids
          $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
          $get_post_comment = mysqli_query($connection, $query);
          $row = mysqli_fetch_assoc($get_post_comment);
          $count_comment = mysqli_num_rows($get_post_comment);

          echo "<td><a href='post_comment.php?id=$post_id'>$count_comment</a></td>
            <td>$post_date</td>
            <td><a href='../post.php?p_id={$post_id}' class='btn btn-primary'>View</a></td>
            <td><a href='posts.php?source=edit_post&p_id={$post_id}' class='btn btn-info'>Edit</a></td>";
          ?>

          <!-- secure method for delete -->
          <form action="" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

            <?php
            echo '<td><input type="submit" name="delete" value="Delete" class="btn btn-danger"></td>';
            ?>

          </form>

        <?php
          // this also works bt above is secure
          // echo "<td><a rel='$post_id' href='' class='delete_link'>Delete</a></td>";
          //below is also great but above is with great modal
          // <td><a onClick=\"javascript: return confirm('Are you sure?');\" href='posts.php?delete={$post_id}'>Delete</a></td>
          echo "<td>{$post_views_count}</td>
          </tr>";
        }
        // in above delete ask user for confirmation
        ?>
    </tbody>
  </table>
</form>

<?php

if (isset($_POST['post_id'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_post_id = escape($_POST['post_id']);
      $query = "DELETE FROM posts WHERE post_id = $the_post_id";
      $delete_post = mysqli_query($connection, $query);
      header("Location: posts.php");      //reload page after delete
    }
  }
}

?>

<!-- opening modal for delete confirmation -->
<script>
  $(document).ready(function() {
    $('.delete_link').on('click', function(e) {

      //remember this keyword doesnt work in arrow funcs
      var id = $(this).attr('rel');
      var delete_url = "posts.php?delete=" + id;

      //change <a> tag link in modal to above
      $(".modal_delete_link").attr('href', delete_url);

      //for showing/opening modal when user clicks delete button
      $('#myModal').modal('show');

      e.preventDefault(); //prevent default behaviour of link
    });
  });
</script>