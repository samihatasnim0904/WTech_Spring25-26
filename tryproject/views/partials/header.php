<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store - Task 4</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">🛍️ ShopHub</div>
        <div class="nav-links">
            <a href="/product/catalogue">📦 Products</a>
            <a href="/orders/my-orders">📋 My Orders</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/admin/orders">⚙️ Admin Orders</a>
            <?php endif; ?>
            <span class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['name'] ?? 'Customer'); ?></span>
        </div>
    </nav>
    <div class="container"></div>