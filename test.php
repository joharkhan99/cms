<?php include("includes/header.php") ?>
<?php include("includes/db.php") ?>

<?php

echo loggedInUserId();

if (userLikedThisPost(78))
  echo "liked it";
else
  echo "nah";;

?>