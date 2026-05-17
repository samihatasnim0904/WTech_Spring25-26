<?php
// Product Detail Page with Average Rating and Reviews
session_start();
require_once __DIR__ . '/../Model/Review.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['id'])) {
    header('Location: /WTech_Spring25-26/Project_4_Task_04/View/index.php');
    exit();
}

$product_id = $_GET['id'];
$database = new Database();
$db = $database->getConnection();
$reviewModel = new Review($db);

// Get product details
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = :id AND p.is_available = 1";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: /WTech_Spring25-26/Project_4_Task_04/View/index.php');
    exit();
}

// Get average rating and reviews
$ratingStats = $reviewModel->getAverageRating($product_id);
$reviews = $reviewModel->getProductReviews($product_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 1rem; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .product-detail { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 2rem; }
        .product-image img { width: 100%; max-height: 400px; object-fit: contain; border-radius: 8px; }
        .product-info h1 { margin-bottom: 1rem; color: #333; }
        .price { font-size: 2rem; color: #28a745; font-weight: bold; margin: 1rem 0; }
        .description { color: #666; line-height: 1.6; margin: 1rem 0; }
        .stock { margin: 1rem 0; padding: 0.5rem; border-radius: 4px; display: inline-block; }
        .in-stock { background: #d4edda; color: #155724; }
        .out-of-stock { background: #f8d7da; color: #721c24; }
        .category { color: #666; margin-bottom: 1rem; }
        .rating-summary { background: #f8f9fa; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .average-rating { font-size: 2rem; font-weight: bold; color: #ffc107; display: inline-block; margin-right: 1rem; }
        .review-count { color: #666; }
        .stars-display { color: #ffc107; font-size: 1.2rem; margin: 0.5rem 0; }
        .add-to-cart { background: #007bff; color: white; border: none; padding: 0.75rem 2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; margin-top: 1rem; }
        .add-to-cart:hover { background: #0056b3; }
        .reviews-section { margin-top: 2rem; background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .review-item { border-bottom: 1px solid #e0e0e0; padding: 1rem; margin-bottom: 0.5rem; }
        .review-author { font-weight: bold; color: #333; }
        .review-rating { color: #ffc107; margin-left: 0.5rem; }
        .review-date { color: #666; font-size: 0.8rem; margin-left: 0.5rem; }
        .review-text { margin-top: 0.5rem; color: #555; }
        .no-reviews { text-align: center; padding: 2rem; color: #666; }
        @media (max-width: 768px) { .product-detail { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <div><h2>🛍️ E-Commerce Store</h2></div>
        <div>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/index.php">🏠 Home</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/cart.php">🛒 Cart</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/WTech_Spring25-26/Project_4_Task_04/Controller/OrderController.php?action=my-orders">📦 My Orders</a>
                <a href="/WTech_Spring25-26/Project_4_Task_04/View/profile.php">👤 Profile</a>
                <a href="/WTech_Spring25-26/Project_4_Task_04/View/logout.php">🚪 Logout</a>
            <?php else: ?>
                <a href="/WTech_Spring25-26/Project_4_Task_04/View/login.php">🔐 Login</a>
                <a href="/WTech_Spring25-26/Project_4_Task_04/View/register.php">📝 Register</a>
            <?php endif; ?>
        </div>
    </nav>
    
    <div class="container">
        <div class="product-detail">
            <div class="product-image">
                <?php if ($product['primary_image_path']): ?>
                    <img src="/uploads/<?php echo htmlspecialchars($product['primary_image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <img src="/WTech_Spring25-26/Project_4_Task_04/images/placeholder.png" alt="No image available">
                <?php endif; ?>
            </div>
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="category">📂 Category: <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></div>
                
                <div class="rating-summary">
                    <div class="stars-display">
                        <?php 
                        $fullStars = floor($ratingStats['average']);
                        $halfStar = ($ratingStats['average'] - $fullStars) >= 0.5;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $fullStars) {
                                echo '⭐';
                            } elseif ($halfStar && $i == $fullStars + 1) {
                                echo '½';
                            } else {
                                echo '☆';
                            }
                        }
                        ?>
                    </div>
                    <span class="average-rating"><?php echo $ratingStats['average']; ?></span>
                    <span class="review-count">(<?php echo $ratingStats['count']; ?> reviews)</span>
                </div>
                
                <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                <div class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>
                <div class="stock <?php echo $product['stock_qty'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                    <?php echo $product['stock_qty'] > 0 ? "✅ In Stock ({$product['stock_qty']} available)" : '❌ Out of Stock'; ?>
                </div>
                <?php if ($product['stock_qty'] > 0): ?>
                    <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">🛒 Add to Cart</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="reviews-section">
            <h2>📖 Customer Reviews</h2>
            <div id="reviewsContainer">
                <?php if (empty($reviews)): ?>
                    <div class="no-reviews">📝 No reviews yet. Be the first to review this product!</div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div>
                                <span class="review-author">👤 <?php echo htmlspecialchars($review['user_name']); ?></span>
                                <span class="review-rating"><?php echo str_repeat('⭐', $review['rating']); ?></span>
                                <span class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <div class="review-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        async function addToCart(productId) {
            try {
                const response = await fetch('/WTech_Spring25-26/Project_4_Task_04/api/cart/add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId })
                });
                const result = await response.json();
                if (response.ok) {
                    alert('✅ Product added to cart!');
                    // Update cart count in navbar if exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) cartCount.textContent = result.total_items;
                } else {
                    alert('❌ ' + (result.error || 'Failed to add to cart'));
                }
            } catch (error) {
                alert('❌ Network error: ' + error.message);
            }
        }
    </script>
</body>
</html>