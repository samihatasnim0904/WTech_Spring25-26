<?php
session_start();
require_once "../Config/helper.php";
require_admin();

require_once "../Model/adminModel.php";

$model = new AdminModel();

/* GET PRODUCT */
$id = $_GET['id'] ?? 0;
$product = $model->getProductById($id);
$categories = $model->getCategories();

if(!$product){
    echo "Product not found";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<link rel="stylesheet" href="../View/Style/adminStyle.css">
<script src="Controller/JS/admin.js"></script>
</head>

<body>

<div class="form-container">
<h2>Edit Product</h2>

<form id="editForm" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?php echo $product['id']; ?>">

<input type="text" name="name" value="<?php echo $product['name']; ?>" required>

<textarea name="description"><?php echo $product['description']; ?></textarea>

<input type="number" name="price" value="<?php echo $product['price']; ?>" required>

<input type="number" name="stock" value="<?php echo $product['stock_qty']; ?>" required>

<!-- CATEGORY -->
<select name="category_id" required>
<option value="">Select Category</option>

<?php while($cat = mysqli_fetch_assoc($categories)){ ?>
<option value="<?php echo $cat['id']; ?>"
<?php if($cat['id'] == $product['category_id']) echo "selected"; ?>>

<?php echo (!empty($cat['parent_id'])) ? "-- ".$cat['name'] : $cat['name']; ?>

</option>
<?php } ?>

</select>

<!-- IMAGE -->
<p>Current Image:</p>

<img src="../uploads/<?php echo $product['image']; ?>" width="100">

<input type="file" name="image">

<button type="button" onclick="updateProduct()">Update</button>

<p id="msg"></p>

</form>

</div>

</body>
</html>