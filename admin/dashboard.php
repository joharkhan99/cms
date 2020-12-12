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
            Welcome To Dashboard

            <small><?php echo strtoupper($_SESSION["username"]); ?></small>
          </h1>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">

        <!-- posts panel  -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-text fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">

                  <?php
                  $post_count = recordCount('posts');
                  ?>

                  <div class="huge"><?php echo $post_count; ?></div>
                  <div>Posts</div>
                </div>
              </div>
            </div>
            <a href="posts.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- comments panel -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-green">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-comments fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">

                  <?php
                  $comment_count = recordCount('comments');
                  ?>

                  <div class="huge"><?php echo $comment_count; ?></div>
                  <div>Comments</div>
                </div>
              </div>
            </div>
            <a href="comments.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- users panel -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-yellow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-user fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">

                  <?php
                  $user_count = recordCount('users');
                  ?>

                  <div class="huge"><?php echo $user_count; ?></div>
                  <div>Users</div>
                </div>
              </div>
            </div>
            <a href="users.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <!-- category panel -->
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-red">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-list fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">

                  <?php
                  $category_count = recordCount('categories');
                  ?>

                  <div class="huge"><?php echo $category_count; ?></div>
                  <div>Categories</div>
                </div>
              </div>
            </div>
            <a href="categories.php">
              <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

      </div>
      <!-- ./row -->

      <?php
      $post_publish_count = checkStatus('posts', 'post_status', 'published');
      $post_draft_count = checkStatus('posts', 'post_status', 'draft');
      $count_unapprove_comments = checkStatus('comments', 'comment_status', 'unapproved');
      $count_subscribers = checkStatus('users', 'user_role', 'Subscriber');
      $count_admins = checkStatus('users', 'user_role', 'Admin');

      ?>

      <div class="row">
        <script type="text/javascript">
          google.charts.load('current', {
            'packages': ['bar']
          });
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Data', 'Count'],

              <?php

              $element_text = ['All Posts', 'Published Posts', 'Draft Posts', 'Categories', 'Users', 'Subscribers', 'Admins', 'Approved Comments', 'Unapproved Comments'];
              $element_count = [$post_count, $post_publish_count, $post_draft_count, $category_count, $user_count, $count_subscribers, $count_admins, $comment_count, $count_unapprove_comments];

              for ($i = 0; $i < count($element_text); $i++) {
                echo "[ '{$element_text[$i]}' " . "," . " {$element_count[$i]} ], ";
              }

              ?>

              // ['Posts', 1000],     //above php is for this bcz we want data from db
            ]);

            var options = {
              chart: {
                title: '',
                subtitle: '',
              }
            };

            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        </script>

        <div id="columnchart_material" style="width: auto; height: 500px;"></div>

      </div>

    </div>
    <!-- /.container-fluid -->

  </div>
  <!-- /#page-wrapper -->
  <?php include "includes/admin_footer.php" ?>

  <!-- toastr css file and js to show notification -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>

  <!-- pusher js library -->
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

  <!-- for pusher notification -->
  <script>
    $(document).ready(function() {
      const pusher = new Pusher("196c42907c3d72316513", {
        cluster: 'ap2',
        encrypted: true
      });

      const notificationChannel = pusher.subscribe('notifications');
      notificationChannel.bind('new_user', (notification) => {
        var message = notification.message;

        toastr.success(`${message} just registered.`);
      })
    });
  </script>