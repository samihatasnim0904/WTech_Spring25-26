// Order detail toggle
document.querySelectorAll('.order-row').forEach(row => {
    row.addEventListener('click', async function() {
        const orderId = this.dataset.orderId;
        const detailRow = document.getElementById(`detail-${orderId}`);
        
        if (detailRow.style.display === 'none') {
            // Fetch order details via AJAX
            const response = await fetch(`/orders/detail/${orderId}`);
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
                itemsHtml += '<hr><h4>Leave a Review</h4>';
                for (let item of order.items) {
                    itemsHtml += `
                        <div class="review-form" data-product-id="${item.product_id}">
                            <strong>${item.name}</strong>
                            <div class="star-rating" data-rating="0">
                                <span class="star" data-star="1">☆</span>
                                <span class="star" data-star="2">☆</span>
                                <span class="star" data-star="3">☆</span>
                                <span class="star" data-star="4">☆</span>
                                <span class="star" data-star="5">☆</span>
                            </div>
                            <textarea placeholder="Write your review..." rows="3" style="width:100%; margin:8px 0"></textarea>
                            <button class="submit-review" data-product="${item.product_id}">Submit Review</button>
                            <div class="review-feedback"></div>
                        </div>
                    `;
                }
            }
            
            document.getElementById(`items-${orderId}`).innerHTML = itemsHtml;
            detailRow.style.display = 'table-row';
            
            // Attach star rating and submit handlers
            attachReviewHandlers();
        } else {
            detailRow.style.display = 'none';
        }
    });
});

// Admin order status update
document.querySelectorAll('.update-status-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const orderId = this.dataset.id;
        const select = this.parentElement.querySelector('.status-select');
        const newStatus = select.value;
        
        const response = await fetch(`/api/orders/${orderId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: newStatus })
        });
        
        const result = await response.json();
        if (result.ok) {
            // Update badge in-place
            const badge = document.getElementById(`badge-${orderId}`);
            badge.className = `status-badge status-${newStatus.toLowerCase()}`;
            badge.textContent = newStatus;
            
            // Show success message
            showFlashMessage(`Order #${orderId} updated to ${newStatus}`, 'success');
        }
    });
});

// Review submission handler
function attachReviewHandlers() {
    document.querySelectorAll('.star-rating').forEach(ratingDiv => {
        const stars = ratingDiv.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.dataset.star);
                ratingDiv.dataset.rating = rating;
                stars.forEach((s, idx) => {
                    s.textContent = idx < rating ? '★' : '☆';
                    s.classList.toggle('selected', idx < rating);
                });
            });
        });
    });
    
    document.querySelectorAll('.submit-review').forEach(btn => {
        btn.addEventListener('click', async function() {
            const reviewForm = this.closest('.review-form');
            const productId = reviewForm.dataset.productId;
            const rating = reviewForm.querySelector('.star-rating').dataset.rating || 0;
            const reviewText = reviewForm.querySelector('textarea').value;
            const feedbackDiv = reviewForm.querySelector('.review-feedback');
            
            if (rating == 0) {
                feedbackDiv.innerHTML = '<span class="error">Please select a rating</span>';
                return;
            }
            
            if (!reviewText.trim()) {
                feedbackDiv.innerHTML = '<span class="error">Please write a review</span>';
                return;
            }
            
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
                feedbackDiv.innerHTML = '<span class="success">✅ Review submitted successfully!</span>';
                reviewForm.querySelector('textarea').disabled = true;
                reviewForm.querySelectorAll('.star').forEach(s => s.style.pointerEvents = 'none');
                btn.disabled = true;
            } else {
                feedbackDiv.innerHTML = `<span class="error">❌ ${result.error || 'Failed to submit review'}</span>`;
            }
        });
    });
}

// Admin filters
const applyFilters = () => {
    const status = document.getElementById('statusFilter')?.value;
    const dateFrom = document.getElementById('dateFrom')?.value;
    const dateTo = document.getElementById('dateTo')?.value;
    
    if (!status) return;
    
    const rows = document.querySelectorAll('#ordersTableBody tr');
    rows.forEach(row => {
        const orderStatus = row.querySelector('.status-badge')?.textContent;
        const orderDate = row.cells[1]?.textContent;
        
        let show = true;
        if (status !== 'all' && orderStatus !== status) show = false;
        if (dateFrom && orderDate < dateFrom) show = false;
        if (dateTo && orderDate > dateTo) show = false;
        
        row.style.display = show ? '' : 'none';
    });
};

document.getElementById('applyFilters')?.addEventListener('click', applyFilters);
document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
document.getElementById('dateFrom')?.addEventListener('change', applyFilters);
document.getElementById('dateTo')?.addEventListener('change', applyFilters);

// Flash message helper
function showFlashMessage(message, type) {
    const flash = document.createElement('div');
    flash.className = `flash ${type}`;
    flash.textContent = message;
    document.querySelector('.container').prepend(flash);
    setTimeout(() => flash.remove(), 3000);
}

// Initialize review handlers on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachReviewHandlers);
} else {
    attachReviewHandlers();
}
// In the order detail fetch, change:
const response = await fetch(`index.php?route=orders/detail/${orderId}`);

// In the review submission, change:
const response = await fetch('index.php?route=api/reviews', {
    method: 'POST',
    body: formData
});

// In the admin status update, change:
const response = await fetch(`index.php?route=api/orders/${orderId}`, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ status: newStatus })
});