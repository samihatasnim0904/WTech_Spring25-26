// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    // Order detail toggle - Customer My Orders page
    const orderRows = document.querySelectorAll('.order-row');
    orderRows.forEach(row => {
        row.addEventListener('click', async function() {
            const orderId = this.dataset.orderId;
            const detailRow = document.getElementById(`detail-${orderId}`);
            
            if (detailRow.style.display === 'none' || !detailRow.style.display) {
                // Fetch order details
                try {
                    const response = await fetch(`orders/detail/${orderId}`);
                    const order = await response.json();
                    
                    let itemsHtml = '<h4>Order Items</h4><ul>';
                    if (order.items && order.items.length > 0) {
                        order.items.forEach(item => {
                            itemsHtml += `<li>${item.quantity} x ${item.name} @ $${item.unit_price} = $${(item.quantity * item.unit_price).toFixed(2)}</li>`;
                        });
                    }
                    itemsHtml += '</ul>';
                    
                    // Show review forms for Delivered orders
                    if (order.status === 'Delivered') {
                        itemsHtml += '<hr><h4>📝 Leave a Review</h4>';
                        for (let item of order.items) {
                            itemsHtml += `
                                <div class="review-form" data-product-id="${item.product_id}" data-product-name="${item.name}">
                                    <strong>${item.name}</strong>
                                    <div class="star-rating" data-rating="0">
                                        <span class="star" data-star="1">☆</span>
                                        <span class="star" data-star="2">☆</span>
                                        <span class="star" data-star="3">☆</span>
                                        <span class="star" data-star="4">☆</span>
                                        <span class="star" data-star="5">☆</span>
                                    </div>
                                    <textarea placeholder="Write your review..." rows="3" style="width:100%; margin:8px 0; padding:8px; border:1px solid #d0d7de; border-radius:6px;"></textarea>
                                    <button class="submit-review-btn" data-product-id="${item.product_id}">Submit Review</button>
                                    <div class="review-feedback" style="margin-top:8px;"></div>
                                </div>
                            `;
                        }
                    }
                    
                    document.getElementById(`items-${orderId}`).innerHTML = itemsHtml;
                    detailRow.style.display = 'table-row';
                    
                    // Attach star rating functionality to the newly added review forms
                    attachStarRatingHandlers();
                    
                    // Attach review submission handlers
                    attachReviewSubmissionHandlers();
                    
                } catch (error) {
                    console.error('Error fetching order details:', error);
                    document.getElementById(`items-${orderId}`).innerHTML = '<p style="color:red;">Error loading order details</p>';
                    detailRow.style.display = 'table-row';
                }
            } else {
                detailRow.style.display = 'none';
            }
        });
    });
    
    // Star rating handler function
    function attachStarRatingHandlers() {
        const starRatings = document.querySelectorAll('.star-rating');
        starRatings.forEach(ratingDiv => {
            const stars = ratingDiv.querySelectorAll('.star');
            stars.forEach(star => {
                star.removeEventListener('click', starClickHandler);
                star.addEventListener('click', starClickHandler);
            });
        });
    }
    
    function starClickHandler() {
        const rating = parseInt(this.dataset.star);
        const ratingDiv = this.closest('.star-rating');
        const stars = ratingDiv.querySelectorAll('.star');
        
        ratingDiv.dataset.rating = rating;
        stars.forEach((star, idx) => {
            if (idx < rating) {
                star.textContent = '★';
                star.classList.add('selected');
            } else {
                star.textContent = '☆';
                star.classList.remove('selected');
            }
        });
    }
    
    // Review submission handler
    function attachReviewSubmissionHandlers() {
        const submitButtons = document.querySelectorAll('.submit-review-btn');
        submitButtons.forEach(btn => {
            btn.removeEventListener('click', reviewSubmitHandler);
            btn.addEventListener('click', reviewSubmitHandler);
        });
    }
    
    async function reviewSubmitHandler() {
        const reviewForm = this.closest('.review-form');
        const productId = reviewForm.dataset.productId;
        const ratingDiv = reviewForm.querySelector('.star-rating');
        const rating = ratingDiv.dataset.rating || 0;
        const reviewText = reviewForm.querySelector('textarea').value.trim();
        const feedbackDiv = reviewForm.querySelector('.review-feedback');
        
        // Validation
        if (rating == 0) {
            feedbackDiv.innerHTML = '<span style="color:#cf222e;">❌ Please select a star rating</span>';
            return;
        }
        
        if (reviewText === '') {
            feedbackDiv.innerHTML = '<span style="color:#cf222e;">❌ Please write a review</span>';
            return;
        }
        
        // Disable button while submitting
        this.disabled = true;
        this.textContent = 'Submitting...';
        
        try {
            // Create form data for POST request
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('rating', rating);
            formData.append('review_text', reviewText);
            
            const response = await fetch('/api/reviews', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.ok) {
                feedbackDiv.innerHTML = '<span style="color:#1a7f37;">✅ Review submitted successfully! Thank you for your feedback.</span>';
                // Disable the form elements
                reviewForm.querySelectorAll('.star').forEach(star => {
                    star.style.pointerEvents = 'none';
                    star.style.opacity = '0.5';
                });
                reviewForm.querySelector('textarea').disabled = true;
                this.disabled = true;
                this.textContent = 'Submitted';
                
                // Optionally refresh the reviews section on product detail page
                refreshProductReviews(productId);
            } else {
                feedbackDiv.innerHTML = `<span style="color:#cf222e;">❌ ${result.error || 'Failed to submit review. Please try again.'}</span>`;
                this.disabled = false;
                this.textContent = 'Submit Review';
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            feedbackDiv.innerHTML = '<span style="color:#cf222e;">❌ Network error. Please try again.</span>';
            this.disabled = false;
            this.textContent = 'Submit Review';
        }
    }
    
    // Function to refresh product reviews on the detail page
    async function refreshProductReviews(productId) {
        // Check if we're on a product detail page
        const reviewsContainer = document.getElementById('reviewsList');
        if (reviewsContainer) {
            try {
                const response = await fetch(`/api/products/${productId}/reviews`);
                const reviews = await response.json();
                
                if (reviews.length > 0) {
                    let reviewsHtml = '';
                    reviews.forEach(review => {
                        reviewsHtml += `
                            <div class="review-item">
                                <div class="review-rating">${'⭐'.repeat(review.rating)}</div>
                                <div class="review-text">"${escapeHtml(review.review_text)}"</div>
                                <div class="review-meta">- User #${review.user_id} on ${review.created_at}</div>
                            </div>
                        `;
                    });
                    reviewsContainer.innerHTML = reviewsHtml;
                }
            } catch (error) {
                console.error('Error refreshing reviews:', error);
            }
        }
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Admin Order Management - Status Update with PUT request
    const updateButtons = document.querySelectorAll('.update-status-btn');
    updateButtons.forEach(btn => {
        btn.addEventListener('click', async function() {
            const orderId = this.dataset.id;
            const select = this.parentElement.querySelector('.status-select');
            const newStatus = select.value;
            const originalText = this.textContent;
            
            // Disable button during update
            this.disabled = true;
            this.textContent = 'Updating...';
            
            try {
                const response = await fetch(`/api/orders/${orderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                
                const result = await response.json();
                
                if (result.ok) {
                    // Update badge in-place without page reload
                    const badge = document.getElementById(`badge-${orderId}`);
                    if (badge) {
                        // Remove old status classes
                        badge.className = `status-badge status-${newStatus.toLowerCase()}`;
                        badge.textContent = newStatus;
                    }
                    
                    // Show success message
                    showFlashMessage(`Order #${orderId} status updated to ${newStatus}`, 'success');
                    
                    // If status is cancelled and it was delivered, update review availability
                    if (newStatus === 'Cancelled') {
                        const orderRow = document.querySelector(`tr[data-order-id="${orderId}"]`);
                        if (orderRow) {
                            orderRow.style.opacity = '0.7';
                        }
                    }
                } else {
                    showFlashMessage(`Failed to update order #${orderId}`, 'error');
                }
            } catch (error) {
                console.error('Error updating order:', error);
                showFlashMessage(`Network error while updating order #${orderId}`, 'error');
            } finally {
                this.disabled = false;
                this.textContent = originalText;
            }
        });
    });
    
    // Admin Filters functionality
    const applyFiltersBtn = document.getElementById('applyFilters');
    const statusFilter = document.getElementById('statusFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    
    function applyFilters() {
        const status = statusFilter ? statusFilter.value : 'all';
        const fromDate = dateFrom ? dateFrom.value : '';
        const toDate = dateTo ? dateTo.value : '';
        const rows = document.querySelectorAll('#ordersTableBody tr');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            const statusBadge = row.querySelector('.status-badge');
            const orderStatus = statusBadge ? statusBadge.textContent : '';
            const orderDate = row.cells[1] ? row.cells[1].textContent : '';
            
            let show = true;
            
            // Filter by status
            if (status !== 'all' && orderStatus !== status) {
                show = false;
            }
            
            // Filter by date range
            if (show && fromDate && orderDate < fromDate) {
                show = false;
            }
            
            if (show && toDate && orderDate > toDate) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Show message if no results
        const tbody = document.getElementById('ordersTableBody');
        let noResultsMsg = document.getElementById('noResultsMsg');
        
        if (visibleCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('tr');
                noResultsMsg.id = 'noResultsMsg';
                noResultsMsg.innerHTML = `<td colspan="6" style="text-align:center; padding:2rem;">No orders match the selected filters</td>`;
                tbody.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', applyFilters);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    if (dateFrom) {
        dateFrom.addEventListener('change', applyFilters);
    }
    if (dateTo) {
        dateTo.addEventListener('change', applyFilters);
    }
    
    // Flash message helper function
    function showFlashMessage(message, type) {
        const flash = document.createElement('div');
        flash.className = `flash flash-${type}`;
        flash.style.cssText = `
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 8px;
            font-size: 14px;
            animation: slideDown 0.3s ease;
        `;
        
        if (type === 'success') {
            flash.style.backgroundColor = '#d1fae5';
            flash.style.borderLeft = '4px solid #1a7f37';
            flash.style.color = '#1a7f37';
        } else {
            flash.style.backgroundColor = '#ffe3e3';
            flash.style.borderLeft = '4px solid #cf222e';
            flash.style.color = '#cf222e';
        }
        
        flash.textContent = message;
        
        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(flash, container.firstChild);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transition = 'opacity 0.3s';
                setTimeout(() => flash.remove(), 300);
            }, 3000);
        }
    }
    
    // Add CSS animation for flash messages
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .flash {
            animation: slideDown 0.3s ease;
        }
        
        .review-form {
            transition: all 0.3s ease;
        }
        
        .submit-review-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .star {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .star:hover {
            transform: scale(1.1);
        }
    `;
    document.head.appendChild(style);
    
    // Initial load - attach handlers to any existing review forms
    attachStarRatingHandlers();
    attachReviewSubmissionHandlers();
});