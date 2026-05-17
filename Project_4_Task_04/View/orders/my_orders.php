<?php
// My Orders Page - Customer view with immediate review rendering
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - E-Commerce Store</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 1rem; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .page-title { margin-bottom: 2rem; color: #333; }
        .orders-list { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .order-item { border-bottom: 1px solid #e0e0e0; padding: 1rem; cursor: pointer; transition: background 0.3s; }
        .order-item:hover { background: #f9f9f9; }
        .order-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .order-info { flex: 1; }
        .order-id { font-weight: bold; color: #333; margin-bottom: 0.5rem; }
        .order-date { color: #666; font-size: 0.9rem; }
        .order-total { font-weight: bold; color: #28a745; font-size: 1.1rem; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; margin-left: 1rem; }
        .status-Pending { background: #ffc107; color: #333; }
        .status-Processing { background: #17a2b8; color: white; }
        .status-Shipped { background: #007bff; color: white; }
        .status-Delivered { background: #28a745; color: white; }
        .status-Cancelled { background: #dc3545; color: white; }
        .order-details { display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0; }
        .order-details.active { display: block; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        .items-table th, .items-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #e0e0e0; }
        .items-table th { background: #f8f9fa; font-weight: bold; }
        .product-image { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .review-form { margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px; }
        .review-form textarea { width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ddd; border-radius: 4px; resize: vertical; }
        .rating-select { margin: 0.5rem 0; }
        .rating-select label { margin-right: 1rem; cursor: pointer; }
        .btn-submit-review { background: #28a745; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }
        .btn-submit-review:hover { background: #218838; }
        .review-message { margin-top: 0.5rem; font-size: 0.9rem; }
        .review-message.success { color: #28a745; }
        .review-message.error { color: #dc3545; }
        .reviews-list { margin-top: 1rem; max-height: 300px; overflow-y: auto; }
        .review-item { border-bottom: 1px solid #e0e0e0; padding: 0.75rem; margin-bottom: 0.5rem; }
        .review-item:last-child { border-bottom: none; }
        .review-author { font-weight: bold; color: #333; }
        .review-rating { color: #ffc107; margin-left: 0.5rem; }
        .review-date { color: #666; font-size: 0.8rem; margin-left: 0.5rem; }
        .review-text { margin-top: 0.5rem; color: #555; }
        .empty-orders { text-align: center; padding: 3rem; color: #666; }
        .loading { text-align: center; padding: 2rem; }
        .new-review { background: #e8f5e9; border-left: 4px solid #28a745; padding: 0.75rem; margin-top: 0.5rem; }
        .error-message { color: #dc3545; padding: 1rem; text-align: center; }
        .order-summary { margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px; }
        button:disabled { opacity: 0.6; cursor: not-allowed; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo"><h2>🛍️ E-Commerce Store</h2></div>
        <div>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/index.php">🏠 Product Catalogue</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/cart.php">🛒 Cart</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/Controller/OrderController.php?action=my-orders">📦 My Orders</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/profile.php">👤 Profile</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/logout.php">🚪 Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <h1 class="page-title">📋 My Orders</h1>
        
        <div class="orders-list" id="ordersList">
            <?php if (empty($orders)): ?>
                <div class="empty-orders">
                    <p>📭 You haven't placed any orders yet.</p>
                    <a href="/WTech_Spring25-26/Project_4_Task_04/View/index.php" style="display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">🛍️ Start Shopping</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item" data-order-id="<?php echo $order['id']; ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-id">Order #<?php echo $order['id']; ?></div>
                                <div class="order-date"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></div>
                            </div>
                            <div class="order-total">$<?php echo number_format($order['total_amount'], 2); ?></div>
                            <div class="status-badge status-<?php echo $order['status']; ?>"><?php echo $order['status']; ?></div>
                        </div>
                        <div class="order-details" id="details-<?php echo $order['id']; ?>">
                            <div class="loading">⏳ Loading order details...</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.order-item').forEach(item => {
            item.addEventListener('click', async (e) => {
                if (e.target.closest('.review-form')) return;
                
                const orderId = item.dataset.orderId;
                const detailsDiv = document.getElementById(`details-${orderId}`);
                
                if (detailsDiv.classList.contains('active')) {
                    detailsDiv.classList.remove('active');
                    return;
                }
                
                document.querySelectorAll('.order-details.active').forEach(div => div.classList.remove('active'));
                
                if (!detailsDiv.dataset.loaded) {
                    try {
                        const response = await fetch(`/WTech_Spring25-26/Project_4_Task_04/Controller/OrderController.php?action=get-details&order_id=${orderId}`);
                        const order = await response.json();
                        
                        if (response.ok) {
                            renderOrderDetails(order, detailsDiv);
                            detailsDiv.dataset.loaded = 'true';
                        } else {
                            detailsDiv.innerHTML = `<div class="error-message">❌ Failed to load order details: ${order.error || 'Unknown error'}</div>`;
                        }
                    } catch (error) {
                        detailsDiv.innerHTML = `<div class="error-message">❌ Network error: ${error.message}</div>`;
                    }
                }
                
                detailsDiv.classList.add('active');
            });
        });
        
        function renderOrderDetails(order, container) {
            let itemsHtml = `
                <table class="items-table">
                    <thead><tr><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Total</th><th></th></tr></thead>
                    <tbody>
            `;
            
            order.items.forEach(item => {
                const itemTotal = item.quantity * item.unit_price;
                itemsHtml += `
                    <tr class="order-item-row-${item.product_id}">
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                ${item.primary_image_path ? `<img src="/uploads/${item.primary_image_path}" alt="${escapeHtml(item.product_name)}" class="product-image">` : ''}
                                <strong>${escapeHtml(item.product_name)}</strong>
                            </div>
                        </td>
                        <td>${item.quantity}</td>
                        <td>$${parseFloat(item.unit_price).toFixed(2)}</td>
                        <td>$${itemTotal.toFixed(2)}</td>
                        <td>
                            ${item.can_review ? `
                                <button class="btn-submit-review" onclick="showReviewForm(event, ${item.product_id}, '${escapeHtml(item.product_name)}')">
                                    ⭐ Leave a Review
                                </button>
                            ` : (order.status === 'Delivered' ? '✓ Reviewed' : '')}
                         </td>
                    </tr>
                    <tr class="review-row-${item.product_id}" style="display: none;">
                        <td colspan="5">
                            <div class="review-form" id="review-form-${item.product_id}">
                                <h4>✍️ Write Review for: ${escapeHtml(item.product_name)}</h4>
                                <div class="rating-select">
                                    <label>Rating:</label>
                                    <label><input type="radio" name="rating-${item.product_id}" value="1"> ⭐ 1</label>
                                    <label><input type="radio" name="rating-${item.product_id}" value="2"> ⭐⭐ 2</label>
                                    <label><input type="radio" name="rating-${item.product_id}" value="3"> ⭐⭐⭐ 3</label>
                                    <label><input type="radio" name="rating-${item.product_id}" value="4"> ⭐⭐⭐⭐ 4</label>
                                    <label><input type="radio" name="rating-${item.product_id}" value="5"> ⭐⭐⭐⭐⭐ 5</label>
                                </div>
                                <textarea rows="3" placeholder="Share your experience with this product..." id="review-text-${item.product_id}"></textarea>
                                <button onclick="submitReview(${item.product_id})">📝 Submit Review</button>
                                <button onclick="hideReviewForm(${item.product_id})">Cancel</button>
                                <div class="review-message" id="review-message-${item.product_id}"></div>
                                <div class="reviews-list" id="reviews-list-${item.product_id}"></div>
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="5"><div id="review-display-${item.product_id}"></div></td></tr>
                `;
            });
            
            itemsHtml += `
                    </tbody>
                </table>
                <div class="order-summary">
                    <strong>📦 Shipping Address:</strong> ${escapeHtml(order.shipping_address)}<br>
                    <strong>💳 Payment Method:</strong> ${order.payment_method}<br>
                    <strong>📅 Order Date:</strong> ${new Date(order.created_at).toLocaleString()}
                </div>
            `;
            
            container.innerHTML = itemsHtml;
            
            // Load existing reviews for each product
            order.items.forEach(item => {
                loadProductReviews(item.product_id);
            });
        }
        
        async function loadProductReviews(productId) {
            try {
                // Using proper REST API endpoint
                const response = await fetch(`/WTech_Spring25-26/Project_4_Task_04/api/products/${productId}/reviews`);
                const data = await response.json();
                
                const reviewsContainer = document.getElementById(`reviews-list-${productId}`);
                if (reviewsContainer && data.reviews && data.reviews.length > 0) {
                    let reviewsHtml = `<h5>📖 Customer Reviews (${data.review_count}):</h5><div style="max-height: 200px; overflow-y: auto;">`;
                    data.reviews.forEach(review => {
                        reviewsHtml += `
                            <div class="review-item">
                                <div>
                                    <span class="review-author">👤 ${escapeHtml(review.user_name)}</span>
                                    <span class="review-rating">${'⭐'.repeat(review.rating)}</span>
                                    <span class="review-date">${new Date(review.created_at).toLocaleDateString()}</span>
                                </div>
                                <div class="review-text">${escapeHtml(review.review_text)}</div>
                            </div>
                        `;
                    });
                    reviewsHtml += `</div>`;
                    reviewsContainer.innerHTML = reviewsHtml;
                } else if (reviewsContainer) {
                    reviewsContainer.innerHTML = '<p class="review-text" style="color: #666;">📝 No reviews yet. Be the first to review!</p>';
                }
            } catch (error) {
                console.error('Failed to load reviews:', error);
            }
        }
        
        function showReviewForm(event, productId, productName) {
            event.stopPropagation();
            const reviewRow = document.querySelector(`.review-row-${productId}`);
            if (reviewRow) {
                document.querySelectorAll('[class^="review-row-"]').forEach(row => row.style.display = 'none');
                reviewRow.style.display = 'table-row';
                reviewRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        function hideReviewForm(productId) {
            const reviewRow = document.querySelector(`.review-row-${productId}`);
            if (reviewRow) {
                reviewRow.style.display = 'none';
                const radios = document.querySelectorAll(`input[name="rating-${productId}"]`);
                radios.forEach(radio => radio.checked = false);
                document.getElementById(`review-text-${productId}`).value = '';
                const messageDiv = document.getElementById(`review-message-${productId}`);
                if (messageDiv) messageDiv.innerHTML = '';
            }
        }
        
        async function submitReview(productId) {
            let rating = null;
            const radios = document.querySelectorAll(`input[name="rating-${productId}"]`);
            for (let radio of radios) {
                if (radio.checked) {
                    rating = parseInt(radio.value);
                    break;
                }
            }
            
            const reviewText = document.getElementById(`review-text-${productId}`).value;
            const messageDiv = document.getElementById(`review-message-${productId}`);
            
            // Clear previous messages
            messageDiv.innerHTML = '';
            
            // Validation with inline error messages
            if (!rating) {
                messageDiv.innerHTML = '<span class="error">❌ Please select a rating (1-5 stars)</span>';
                return;
            }
            
            if (!reviewText.trim()) {
                messageDiv.innerHTML = '<span class="error">❌ Please enter your review</span>';
                return;
            }
            
            if (reviewText.trim().length < 5) {
                messageDiv.innerHTML = '<span class="error">❌ Review must be at least 5 characters</span>';
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector(`.review-row-${productId} button:first-of-type`);
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
            }
            
            try {
                const response = await fetch('/WTech_Spring25-26/Project_4_Task_04/api/reviews', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        product_id: productId,
                        rating: rating,
                        review_text: reviewText.trim()
                    })
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    messageDiv.innerHTML = '<span class="success">✅ Review submitted successfully!</span>';
                    
                    // ✅ IMMEDIATELY append the new review to the reviews list (as required)
                    const reviewsContainer = document.getElementById(`reviews-list-${productId}`);
                    if (reviewsContainer) {
                        const currentUser = '<?php echo $_SESSION['name'] ?? 'You'; ?>';
                        const newReviewHtml = `
                            <div class="review-item new-review">
                                <div>
                                    <span class="review-author">👤 ${escapeHtml(currentUser)}</span>
                                    <span class="review-rating">${'⭐'.repeat(rating)}</span>
                                    <span class="review-date">Just now</span>
                                </div>
                                <div class="review-text">${escapeHtml(reviewText.trim())}</div>
                            </div>
                        `;
                        
                        // Remove "no reviews" message if exists
                        if (reviewsContainer.innerHTML.includes('No reviews yet')) {
                            reviewsContainer.innerHTML = '<h5>📖 Customer Reviews:</h5><div style="max-height: 200px; overflow-y: auto;"></div>';
                        }
                        
                        const reviewsDiv = reviewsContainer.querySelector('div:last-child') || reviewsContainer;
                        reviewsDiv.insertAdjacentHTML('afterbegin', newReviewHtml);
                    }
                    
                    // Hide the review form after successful submission
                    setTimeout(() => {
                        hideReviewForm(productId);
                        // Update the button to show "Reviewed"
                        const reviewButton = document.querySelector(`.order-item-row-${productId} .btn-submit-review`);
                        if (reviewButton) {
                            reviewButton.textContent = '✓ Reviewed';
                            reviewButton.disabled = true;
                            reviewButton.style.background = '#6c757d';
                            reviewButton.style.cursor = 'default';
                        }
                    }, 1500);
                } else {
                    const errorMsg = result.errors ? result.errors.join(', ') : (result.error || 'Failed to submit review');
                    messageDiv.innerHTML = `<span class="error">❌ ${escapeHtml(errorMsg)}</span>`;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = '📝 Submit Review';
                    }
                }
            } catch (error) {
                messageDiv.innerHTML = `<span class="error">❌ Network error: ${escapeHtml(error.message)}</span>`;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = '📝 Submit Review';
                }
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>