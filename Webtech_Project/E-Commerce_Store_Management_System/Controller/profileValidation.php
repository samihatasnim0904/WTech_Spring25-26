<?php
session_start();
require_once "../Model/profileModel.php";

$model = new ProfileModel();
$id = $_SESSION['user_id'];

$user = $model->getUserById($id);

/* UPDATE PROFILE */
if(isset($_POST['update'])){

    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $addresses = [];
    if(!empty($_POST['address1'])) $addresses[] = $_POST['address1'];
    if(!empty($_POST['address2'])) $addresses[] = $_POST['address2'];

    $model->updateProfile($id, $name, $username, $email, $phone, $addresses);

    header("Location: ../View/profileView.php?success=Profile updated successfully");
    exit();
}


/* CHANGE PASSWORD */
if(isset($_POST['change_password'])){

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    if(password_verify($current, $user['password_hash'])){

        if(strlen($new) < 6){
            header("Location: ../View/profileView.php?error=Password must be at least 6 characters");
            exit();
        }

        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $model->updatePassword($id, $newHash);

        header("Location: ../View/profileView.php?success=Password updated successfully");
        exit();

    } else {
        header("Location: ../View/profileView.php?error=Wrong current password");
        exit();
    }
}