<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <div class="product-detail">
        <div class="product-image-large"><?php echo $product['image']; ?></div>
        <div class="product-info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <div class="price-large">$<?php echo number_format($product['price'], 2); ?></div>
            <div class="rating-large">
                <strong>Average Rating:</strong> ⭐ <?php echo $avgRating > 0 ? $avgRating : 'No ratings yet'; ?>/5
            </div>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <div class="stock">Stock: <?php echo $product['stock']; ?> units available</div>
        </div>
    </div>
    
    <div class="reviews-section">
        <h3>Customer Reviews</h3>
        <div id="reviewsList">
            <?php if (empty($reviews)): ?>
                <p>No reviews yet. Be the first to review this product!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-rating"><?php echo str_repeat('⭐', $review['rating']); ?></div>
                        <div class="review-text">"<?php echo htmlspecialchars($review['review_text']); ?>"</div>
                        <div class="review-meta">- User #<?php echo $review['user_id']; ?> on <?php echo $review['created_at']; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<a href="index.php?route=product/catalogue" class="back-link">← Back to Catalogue</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>