<?php
session_start();
session_destroy();
header('Location: /WTech_Spring25-26/Project_4_Task_04/View/login.php');
exit();
?>