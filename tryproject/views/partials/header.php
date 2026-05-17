<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store - Task 4</title>
    <link rel="stylesheet" href="/tryproject/public/css/style.css">
    <base href="/tryproject/">
</head>
<body>
    <nav class="navbar">
        <div class="logo">🛍️ ShopHub</div>
        <div class="nav-links">
            <a href="index.php?route=product/catalogue">📦 Products</a>
            <a href="index.php?route=orders/my-orders">📋 My Orders</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="index.php?route=admin/orders">⚙️ Admin Orders</a>
            <?php endif; ?>
            <span class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['name'] ?? 'Customer'); ?></span>
        </div>
    </nav>
    <div class="container">