<?php

require_once "../Model/productModel.php";
require_once "../Model/categoryModel.php";

$product = new ProductModel();
$category = new CategoryModel();

if(isset($_GET['search'])){

    $keyword = $_GET['search'];

    // 🔹 CATEGORY SEARCH (use model later if you want cleaner)
    $conn = $category->connection();
    $catSql = "SELECT * FROM categories WHERE name LIKE '%$keyword%'";
    $catResult = mysqli_query($conn, $catSql);

    while($row = mysqli_fetch_assoc($catResult)){

        echo "<a href='../Controller/categoryValidation.php?id=".$row['id']."'>

                <div style='padding:8px; font-weight:bold; background:#f9f9f9;'>

                     Category: ".$row['name']."

                </div>

              </a>";
    }

    // 🔹 PRODUCT SEARCH
    $results = $product->searchProducts($keyword);

    while($row = mysqli_fetch_assoc($results)){

        echo "<a href='../Controller/productValidation.php?id=".$row['id']."'>

                <div style='display:flex; align-items:center; gap:10px; padding:8px;'>

                    <img src='../uploads/".$row['image']."' width='40'>

                    ".$row['name']." - $".$row['price']."

                </div>

              </a>";
    }
}
?>