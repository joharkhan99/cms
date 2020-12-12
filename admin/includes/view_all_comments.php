<table class="table table-bordered table-hover">
  <thead>
    <th>Id</th>
    <th>Author</th>
    <th>Comment</th>
    <th>Email</th>
    <th>Status</th>
    <th>In Response to</th>
    <th>Date</th>
    <th>Approve</th>
    <th>Unapprove</th>
    <th>Delete</th>
  </thead>
  <tbody>
    <tr>

      <?php
      $query = "SELECT * FROM comments ORDER BY comment_id DESC";
      $select_comments = mysqli_query($connection, $query);

      while ($row = mysqli_fetch_assoc($select_comments)) {
        $comment_id = escape($row['comment_id']);
        $comment_post_id = escape($row['comment_post_id']);
        $comment_author = escape($row['comment_author']);
        $comment_content = escape($row['comment_content']);
        $comment_email = escape($row['comment_email']);
        $comment_status = escape($row['comment_status']);
        $comment_date = escape($row['comment_date']);

        // if comment is too long so show less content on front
        if (strlen($comment_content) > 100) {
          $comment_content = substr($row['comment_content'], 0, 100) . " ...";
        }

        echo "
          <tr>
            <td>$comment_id</td>
            <td>$comment_author</td>
            <td style='width: 20%'>$comment_content</td>
            <td>$comment_email</td>
            <td>$comment_status</td>";

        // fill (title/In response to) field in all posts by comparing two cols
        $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
        $select_post = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($select_post)) {
          $post_title = escape($row['post_title']);
          $post_id = escape($row['post_id']);
          echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
        }

        echo "
          <td>$comment_date</td>
          <td><a href='comments.php?approve={$comment_id}'>Approve</a></td>
          <td><a href='comments.php?unapprove={$comment_id}'>Unapprove</a></td>
          <td><a href='comments.php?delete={$comment_id}'>Delete</a></td>
        </tr>";
      }
      ?>
  </tbody>
</table>

<?php

// for aproving comment
if (isset($_GET['approve'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_comment_id = escape($_GET['approve']);
      $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $the_comment_id";
      $approve_comment = mysqli_query($connection, $query);
      header("Location: comments.php");      //reload page after delete
    }
  }
}

// for unproving comment
if (isset($_GET['unapprove'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_comment_id = escape($_GET['unapprove']);
      $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = $the_comment_id ";
      $unapprove_comment = mysqli_query($connection, $query);
      header("Location: comments.php");      //reload page after delete
    }
  }
}

// for delete post
if (isset($_GET['delete'])) {
  if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == "Admin") {
      $the_comment_id = escape($_GET['delete']);
      $query = "DELETE FROM comments WHERE comment_id = $the_comment_id";
      $delete_comment = mysqli_query($connection, $query);
      header("Location: comments.php");      //reload page after delete
    }
  }
}

?>