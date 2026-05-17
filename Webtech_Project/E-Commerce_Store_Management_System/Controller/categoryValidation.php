<?php

require_once "../Model/productModel.php";

$product = new ProductModel();


if(isset($_GET['id'])){

    $id = $_GET['id'];

    $products = $product->getProductsByCategory($id);

    include "../View/categoryView.php";


}elseif(isset($_GET['search'])){

    $keyword = $_GET['search'];

    $products = $product->searchProducts($keyword);

    include "../View/categoryView.php"; // 🔥 SAME PAGE


}else{
    echo "No data found";
}

?>