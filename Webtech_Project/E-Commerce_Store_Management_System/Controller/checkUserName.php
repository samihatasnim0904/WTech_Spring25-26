<?php
require_once('../Model/signupModel.php');

$model = new SignupModel();

$username = $_POST['username'] ?? '';

if(strlen($username) < 5){
    echo "<span style='color:red;'>Too short</span>";
    exit();
}

if($model->checkUsername($username) > 0){
    echo "<span style='color:red;'>Already taken</span>";
} else {
    echo "<span style='color:green;'>Available</span>";
}
?>