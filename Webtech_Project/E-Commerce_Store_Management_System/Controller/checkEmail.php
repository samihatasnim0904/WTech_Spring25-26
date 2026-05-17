<?php
require_once('../Model/signupModel.php');

$model = new SignupModel();

$email = $_POST['email'] ?? '';

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "<span style='color:red;'>Invalid</span>";
    exit();
}

if($model->checkEmail($email) > 0){
    echo "<span style='color:red;'>Email exists</span>";
} else {
    echo "<span style='color:green;'>OK</span>";
}
?>