<?php
require_once "../Model/adminModel.php";

$model = new AdminModel();

/* AJAX ADD */
if(isset($_POST['action']) && $_POST['action'] == "add"){

    if($_FILES['image']['size'] > 3*1024*1024){
        echo "Image must be under 3MB";
        exit();
    }

    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/products/".$image);

    $_POST['image'] = $image;

    $model->addProduct($_POST);

    echo "Product Added Successfully";
}

/* DELETE */
if(isset($_GET['delete'])){
    $model->deleteProduct($_GET['delete']);
    header("Location: ../Admin/adminView.php");
}

/* TOGGLE */
if(isset($_POST['toggle'])){
    $model->toggleStock($_POST['id']);
}

/* UPDATE PRODUCT */
if(isset($_POST['action']) && $_POST['action'] == "update"){

    $model->updateProduct($_POST);

    echo "Product Updated Successfully";
}
?>