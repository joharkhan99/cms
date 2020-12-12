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
            Welcome To Comments
            <small>Author</small>
          </h1>

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
                $post_id = escape($_GET['id']);
                $post_id = mysqli_real_escape_string($connection, $post_id);

                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                $select_comments = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_comments)) {
                  $comment_id = escape($row['comment_id']);
                  $comment_post_id = escape($row['comment_post_id']);
                  $comment_author = escape($row['comment_author']);
                  $comment_content = escape($row['comment_content']);
                  $comment_email = escape($row['comment_email']);
                  $comment_status = escape($row['comment_status']);
                  $comment_date = escape($row['comment_date']);

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
              <td><a href='post_comment.php?approve={$comment_id}&id=" . $_GET['id'] . "'>Approve</a></td>
              <td><a href='post_comment.php?unapprove={$comment_id}&id=" . $_GET['id'] . "'>Unapprove</a></td>
              <td><a href='post_comment.php?delete={$comment_id}&id=" . $_GET['id'] . "'>Delete</a></td>
            </tr>";
                }
                ?>
            </tbody>
          </table>

          <?php

          // for aproving comment
          if (isset($_GET['approve'])) {
            $the_comment_id = escape($_GET['approve']);
            $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $the_comment_id";
            $approve_comment = mysqli_query($connection, $query);
            header("Location: post_comment.php?id=" . $_GET['id']); //reload page after delete
          }

          // for unproving comment
          if (isset($_GET['unapprove'])) {
            $the_comment_id = escape($_GET['unapprove']);
            $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = $the_comment_id ";
            $unapprove_comment = mysqli_query($connection, $query);
            header("Location: post_comment.php?id=" . $_GET['id']); //reload page after delete
          }

          // for delete post
          if (isset($_GET['delete'])) {
            $the_comment_id = escape($_GET['delete']);
            $query = "DELETE FROM comments WHERE comment_id = $the_comment_id";
            $delete_comment = mysqli_query($connection, $query);
            header("Location: post_comment.php?id=" . $_GET['id']); //reload page after delete
          }

          ?>

        </div>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

  </div>
  <!-- /#page-wrapper -->
  <?php include "includes/admin_footer.php" ?>