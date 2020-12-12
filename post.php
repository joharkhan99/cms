<?php include("includes/header.php") ?>
<?php include("includes/db.php") ?>

<!-- Navigation -->
<?php include("includes/navigation.php") ?>

<?php
// LIKING
if (isset($_POST['liked'])) {

    // these fields are sent by ajax when user clicks like btn
    //check at end of this file code
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    //1. select the post and extract its likes col
    $query = "SELECT * FROM posts WHERE post_id=$post_id";
    $postResult = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($postResult);
    $likes = $post['likes'];

    //2. update the post
    mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");

    //3. add/create like for post in likes table
    mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");

    exit();
}

// UNLIKING
if (isset($_POST['unliked'])) {

    // these fields are sent by ajax when user clicks like btn
    //check at end of this file code
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    //1. select the post and extract its likes col
    $query = "SELECT * FROM posts WHERE post_id=$post_id";
    $postResult = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($postResult);
    $likes = $post['likes'];

    //2. delete like
    mysqli_query($connection, "DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");

    //3. update with decrement likes
    mysqli_query($connection, "UPDATE posts SET likes=$likes-1 WHERE post_id=$post_id");

    exit();
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php

            if (isset($_GET['p_id'])) {
                $the_post_id = $_GET['p_id'];

                $views_query = "UPDATE posts SET post_views_count = post_views_count+1 WHERE post_id = $the_post_id";
                $send_query = mysqli_query($connection, $views_query);

                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin') {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
                } else {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status='published'";
                }

                $select_all_posts = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_all_posts)) {
                    $post_title = escape($row['post_title']);
                    $post_author = escape($row['post_author']);
                    $post_date = escape($row['post_date']);
                    $post_image = escape($row['post_image']);
                    $post_content = escape($row['post_content']);
                    $post_views_count = escape($row['post_views_count']);
            ?>
                    <!-- First Blog Post -->
                    <h2>
                        <?php echo $post_title ?>
                    </h2>
                    <p class="lead">
                        by <a href="index.php"><?php echo  $post_author ?></a>
                    </p>
                    <p style="display: inline;"><span class="glyphicon glyphicon-time"></span> <?php echo  $post_date ?></p>
                    <p style="display: inline;"><span class="glyphicon glyphicon-eye-open"></span><?php echo " " . $post_views_count; ?></p>
                    <hr>
                    <img class="img-responsive" width="50%" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="">
                    <hr>
                    <p><?php echo  $post_content ?></p>
                    <hr>

                    <?php if (isLoggedIn()) : ?>

                        <div class="row text-center">
                            <p><a href="" class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like' ?>"><span class="glyphicon glyphicon-thumbs-<?php echo userLikedThisPost($the_post_id) ? 'down' : 'up' ?>" data-toggle="tooltip" data-placement="top" title="<?php echo userLikedThisPost($the_post_id) ? 'U liked this post' : 'like post' ?>"></span></a></p>
                        </div>

                    <?php else : ?>
                        <div class="row text-center">
                            <p class="bg-danger">You need to <a href="login.php">Login</a> to like this post.</p>
                        </div>

                    <?php endif; ?>
                    <div class="row text-center">
                        <p class="bg-info"><b>Likes: <?php echo countPostLikes($the_post_id); ?></b></p>
                    </div>
                <?php
                }
                // comment
                if (isset($_POST['create_comment'])) {
                    $the_post_id = escape($_GET['p_id']);

                    $comment_author = escape($_POST['comment_author']);
                    $comment_email = escape($_POST['comment_email']);
                    $comment_content = escape($_POST['comment_content']);

                    if ($comment_author == '' || $comment_email == '' || $comment_content == '') {
                        echo "<i class='text-danger'>fill all fields</i>";
                    } else {
                        $query = "INSERT INTO comments(comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) VALUES ($the_post_id, '$comment_author', '$comment_email', '$comment_content', 'unapproved', now())";

                        $create_comment_query = mysqli_query($connection, $query);

                        confirmQuery($create_comment_query);

                        //increase comment counts for post
                        // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = $the_post_id";
                        // $update_comment_count = mysqli_query($connection, $query);

                        // confirmQuery($update_comment_count);
                    }
                }
                ?>

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form" action="" method="POST">
                        <div class="form-group">
                            <input type="text" name="comment_author" class="form-control" name="comment_author" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="comment_email" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="3" placeholder="Your Comment" name="comment_content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="create_comment">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php
                $query = "SELECT * FROM comments WHERE comment_post_id=$the_post_id AND comment_status='approved' ORDER BY comment_id DESC";
                $select_comment = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_comment)) {
                    $comment_author = escape($row['comment_author']);
                    $comment_date = escape($row['comment_date']);
                    $comment_content = escape($row['comment_content']);

                ?>

                    <!-- Comment -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $comment_author; ?>
                                <small><?php echo $comment_date; ?></small>
                            </h4>
                            <?php echo $comment_content; ?>
                        </div>
                    </div>
            <?php
                }
            } else {
                header("Location: index.php");
            }
            ?>


        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include("includes/sidebar.php") ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php include("includes/footer.php") ?>

    <!-- POST LIKES AND UNLIKES REQUEST USING AJAX -->
    <script>
        $(document).ready(function() {

            // LIKING
            $('.like').click((e) => {

                var post_id = <?php echo $the_post_id; ?>;
                var user_id = <?php echo loggedInUserId(); ?>;

                $.ajax({
                    type: "post",
                    url: `post.php?p_id=${post_id}`,
                    data: {
                        'liked': 1,
                        'post_id': post_id,
                        'user_id': user_id
                    }
                });
            });

            // UNLIKING
            $('.unlike').click((e) => {

                var post_id = <?php echo $the_post_id; ?>;
                var user_id = <?php echo loggedInUserId(); ?>;

                $.ajax({
                    type: "post",
                    url: `post.php?p_id=${post_id}`,
                    data: {
                        'unliked': 1,
                        'post_id': post_id,
                        'user_id': user_id
                    }
                });
            });

            // for tooltip on like and unlike btn
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

        });
    </script>