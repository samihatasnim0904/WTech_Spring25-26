<?php include __DIR__ . '/../partials/header.php'; ?>

<style>
    /* Product Catalogue Styles */
    .card {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .card h2 {
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        padding: 10px 0;
    }
    
    .product-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        background: #f5f5f5;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 48px;
        color: #999;
    }
    
    .product-card h3 {
        color: #333;
        font-size: 18px;
        margin: 10px 0;
        min-height: 50px;
    }
    
    .price {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
        margin: 10px 0;
    }
    
    .rating {
        color: #ffc107;
        margin: 10px 0;
        font-size: 14px;
    }
    
    .stock {
        color: #28a745;
        font-size: 14px;
        margin: 10px 0;
        font-weight: 500;
    }
    
    .stock-low {
        color: #ffc107;
    }
    
    .stock-out {
        color: #dc3545;
    }
    
    .btn-view {
        display: inline-block;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
        transition: background 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }
    
    .btn-view:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }
    
    .no-products {
        text-align: center;
        padding: 50px;
        color: #666;
        font-size: 18px;
    }
    
    /* Modal Styles for Product Details */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-content {
        background-color: white;
        margin: 50px auto;
        padding: 0;
        width: 90%;
        max-width: 600px;
        border-radius: 10px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.3);
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h3 {
        margin: 0;
    }
    
    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .close:hover {
        transform: scale(1.1);
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .product-detail-image {
        text-align: center;
        font-size: 80px;
        margin: 20px 0;
    }
    
    .product-detail-info {
        margin: 20px 0;
    }
    
    .product-detail-info p {
        margin: 10px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    
    .add-to-cart-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .add-to-cart-btn:hover {
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .card {
            margin: 10px;
            padding: 15px;
        }
        
        .product-image {
            height: 150px;
            font-size: 36px;
        }
        
        .price {
            font-size: 20px;
        }
        
        .modal-content {
            margin: 20px auto;
            width: 95%;
        }
    }
    
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card">
    <h2>📖 Product Catalogue</h2>
    
    <?php if (empty($products)): ?>
        <div class="no-products">
            <p>No products available at the moment.</p>
            <p>Please check back later!</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php 
                        // Display product icon based on category or default
                        if (isset($product['image']) && !empty($product['image'])) {
                            echo htmlspecialchars($product['image']);
                        } else {
                            echo '📦'; // Default product icon
                        }
                        ?>
                    </div>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    <div class="rating">
                        <?php 
                        $avgRating = isset($product['avg_rating']) ? floatval($product['avg_rating']) : 0;
                        if ($avgRating > 0) {
                            $fullStars = floor($avgRating);
                            $halfStar = ($avgRating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            
                            echo str_repeat('⭐', $fullStars);
                            if ($halfStar) echo '½';
                            echo str_repeat('☆', $emptyStars);
                            echo " {$avgRating}/5";
                        } else {
                            echo 'No ratings yet';
                        }
                        ?>
                    </div>
                    <div class="stock <?php 
                        $stock = isset($product['stock']) ? intval($product['stock']) : 0;
                        if ($stock <= 0) {
                            echo 'stock-out';
                        } elseif ($stock < 10) {
                            echo 'stock-low';
                        }
                    ?>">
                        <?php 
                        if ($stock <= 0) {
                            echo '❌ Out of Stock';
                        } elseif ($stock < 10) {
                            echo '⚠️ Low Stock: ' . $stock . ' units';
                        } else {
                            echo '✓ In Stock: ' . $stock . ' units';
                        }
                        ?>
                    </div>
                    <button onclick="showProductDetails(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="btn-view">
                        View Details →
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Product Details Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Product Details</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>
</div>

<script>
function showProductDetails(product) {
    const modal = document.getElementById('productModal');
    const modalBody = document.getElementById('modalBody');
    
    // Create modal content
    const stockStatus = product.stock > 0 ? 
        `<span style="color: #28a745;">✓ In Stock (${product.stock} units)</span>` : 
        `<span style="color: #dc3545;">❌ Out of Stock</span>`;
    
    const ratingHtml = product.avg_rating && product.avg_rating > 0 ?
        `<div style="color: #ffc107; margin: 10px 0;">
            ${'⭐'.repeat(Math.floor(product.avg_rating))}${product.avg_rating % 1 >= 0.5 ? '½' : ''}${'☆'.repeat(5 - Math.ceil(product.avg_rating))} 
            ${product.avg_rating}/5 (${product.review_count || 0} reviews)
        </div>` :
        '<div style="color: #999;">No ratings yet</div>';
    
    modalBody.innerHTML = `
        <div class="product-detail-image">
            ${product.image || '📦'}
        </div>
        <div class="product-detail-info">
            <p><strong>Product Name:</strong> ${escapeHtml(product.name)}</p>
            <p><strong>Price:</strong> <span style="font-size: 24px; color: #007bff;">$${parseFloat(product.price).toFixed(2)}</span></p>
            ${ratingHtml}
            <p><strong>Stock Status:</strong> ${stockStatus}</p>
            <p><strong>Product ID:</strong> #${product.id}</p>
            ${product.description ? `<p><strong>Description:</strong> ${escapeHtml(product.description)}</p>` : ''}
        </div>
        ${product.stock > 0 ? 
            `<button onclick="addToCart(${product.id}, '${escapeHtml(product.name)}', ${product.price})" class="add-to-cart-btn">
                🛒 Add to Cart
            </button>` : 
            `<button class="add-to-cart-btn" style="background: #ccc; cursor: not-allowed;" disabled>
                ❌ Out of Stock
            </button>`
        }
    `;
    
    modal.style.display = 'block';
    
    // Close modal when clicking outside
    modal.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    };
}

function closeModal() {
    const modal = document.getElementById('productModal');
    modal.style.display = 'none';
}

function addToCart(productId, productName, price) {
    // Add to cart logic
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Show success message
    alert(`${productName} has been added to your cart!`);
    
    // Update cart count if function exists
    if (typeof updateCartCount === 'function') {
        updateCartCount();
    }
    
    // Close modal
    closeModal();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>