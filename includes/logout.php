<?php ob_start(); ?>
<!-- start session to store vals in session memeory -->
<?php session_start(); ?>

<?php

//remove everything for user to logout
$_SESSION["username"] = null;
$_SESSION["firstname"] = null;
$_SESSION["lastname"] = null;
$_SESSION["user_role"] = null;

header("Location: ../index.php");
