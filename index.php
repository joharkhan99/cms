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

            $per_page = 5;

            if (isset($_GET['page'])) {
                $page = escape($_GET['page']);
            } else {
                $page = '';
            }

            if ($page == '' || $page == 1) {
                $page_1 = 0;
            } else {
                $page_1 = ($page * $per_page) - $per_page;
            }

            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin') {
                $post_query_count = "SELECT * FROM posts";
            } else {
                $post_query_count = "SELECT * FROM posts WHERE post_status='published'";
            }

            $find_count = mysqli_query($connection, $post_query_count);
            $count = mysqli_num_rows($find_count);

            // show those posts only with post status as published
            if ($count < 1) {
                echo "<h1 class='text-center'>No Posts Available</h1>";
            } else {

                $count = ceil($count / $per_page);

                $query = "SELECT * FROM posts LIMIT $page_1, $per_page";
                $select_all_posts = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_all_posts)) {
                    $post_title = escape($row['post_title']);
                    $post_id = escape($row['post_id']);
                    $post_author = escape($row['post_user']);
                    $post_date = escape($row['post_date']);
                    $post_image = escape($row['post_image']);
                    $post_content = escape($row['post_content']);
                    $post_status = escape($row['post_status']);
                    $post_views_count = escape($row['post_views_count']);
                    // as content is too long so show less content on front
                    if (strlen($post_content) > 100) {
                        $post_content = substr($row['post_content'], 0, 160) . " ...";
                    }

            ?>
                    <!-- First Blog Post -->
                    <h2>
                        <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?php echo  $post_author; ?>&p_id=<?php echo  $post_id ?>"><?php echo  $post_author; ?></a>
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
                        <img class="img-responsive" width="50%" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="">
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
                <?php
                for ($i = 1; $i < $count; $i++) {
                    if ($i == $page) {
                        echo "<li><a class='active' href='index.php?page=$i'>$i</a></li>";
                    } else {
                        echo "<li><a href='index.php?page=$i'>$i</a></li>";
                    }
                }
                ?>
            </ul>

        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include("includes/sidebar.php") ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php include("includes/footer.php") ?>