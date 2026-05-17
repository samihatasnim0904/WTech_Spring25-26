<?php
session_start();

session_unset();
session_destroy();

// remove cookie
setcookie("remember_token", "", time() - 3600, "/");

header("Location: ../View/homeView.php");
?>