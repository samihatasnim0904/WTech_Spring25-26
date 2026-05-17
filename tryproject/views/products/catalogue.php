<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h2>📖 Product Catalogue</h2>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image"><?php echo $product['image']; ?></div>
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                <div class="rating">
                    ⭐ <?php echo $product['avg_rating'] > 0 ? $product['avg_rating'] : 'No ratings'; ?>/5
                </div>
                <div class="stock">Stock: <?php echo $product['stock']; ?></div>
                <a href="product/detail/<?php echo $product['id']; ?>" class="btn-view">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>