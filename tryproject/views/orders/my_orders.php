<?php 
// Ensure $orders is defined with mock data for testing
if (!isset($orders)) {
    // Mock orders data for demonstration
    $orders = [
        [
            'id' => 1001,
            'date' => '2024-01-15',
            'total' => 149.97,
            'status' => 'Delivered'
        ],
        [
            'id' => 1002,
            'date' => '2024-01-20',
            'total' => 89.99,
            'status' => 'Shipped'
        ],
        [
            'id' => 1003,
            'date' => '2024-01-25',
            'total' => 129.99,
            'status' => 'Processing'
        ]
    ];
}

// Handle AJAX request for order details
if (isset($_GET['ajax']) && $_GET['ajax'] == 'order_details') {
    header('Content-Type: application/json');
    
    $orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    
    // Mock order items data based on order ID
    $orderItems = [
        1001 => [
            ['product' => 'Wireless Headphones', 'quantity' => 2, 'price' => 49.99],
            ['product' => 'USB-C Hub', 'quantity' => 1, 'price' => 29.99]
        ],
        1002 => [
            ['product' => 'Smart Watch', 'quantity' => 1, 'price' => 89.99]
        ],
        1003 => [
            ['product' => 'Mechanical Keyboard', 'quantity' => 1, 'price' => 79.99],
            ['product' => 'USB-C Hub', 'quantity' => 2, 'price' => 29.99]
        ]
    ];
    
    $items = isset($orderItems[$orderId]) ? $orderItems[$orderId] : [];
    echo json_encode($items);
    exit;
}

include __DIR__ . '/../partials/header.php'; 
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    /* Header Styles */
    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem 0;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .logo a {
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.3s ease;
    }

    .logo a:hover {
        transform: scale(1.05);
    }

    .nav-menu {
        display: flex;
        gap: 30px;
        align-items: center;
        flex-wrap: wrap;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }

    .nav-link.active {
        background: rgba(255,255,255,0.25);
        border-bottom: 2px solid white;
    }

    .mobile-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 10px;
    }

    /* Main Content */
    .card {
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card h2 {
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-table th,
    .order-table td {
        padding: 15px;
        text-align: left;
    }

    .order-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .order-table th:first-child {
        border-radius: 10px 0 0 0;
    }

    .order-table th:last-child {
        border-radius: 0 10px 0 0;
    }

    .order-table td {
        border-bottom: 1px solid #f0f0f0;
        color: #555;
    }

    .order-row {
        transition: all 0.3s ease;
    }

    .order-row:hover {
        background-color: #f8f9ff;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-delivered {
        background-color: #d4edda;
        color: #155724;
        border-left: 3px solid #28a745;
    }

    .status-processing {
        background-color: #fff3cd;
        color: #856404;
        border-left: 3px solid #ffc107;
    }

    .status-shipped {
        background-color: #cce5ff;
        color: #004085;
        border-left: 3px solid #007bff;
    }

    .status-pending {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 3px solid #dc3545;
    }

    .detail-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .detail-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* Modal Styles */
    .order-modal {
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

    .order-modal-content {
        background-color: white;
        margin: 50px auto;
        padding: 0;
        width: 90%;
        max-width: 800px;
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

    .order-modal-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-modal-header h3 {
        margin: 0;
    }

    .order-modal-close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .order-modal-close:hover {
        transform: scale(1.1);
    }

    .order-modal-body {
        padding: 20px;
        max-height: 500px;
        overflow-y: auto;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table th,
    .items-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .items-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #333;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #667eea;
        font-size: 16px;
    }

    .loading::after {
        content: '...';
        animation: dots 1.5s steps(4, end) infinite;
    }

    @keyframes dots {
        0%, 20% { content: '.'; }
        40% { content: '..'; }
        60%, 100% { content: '...'; }
    }

    .error-message {
        text-align: center;
        padding: 20px;
        color: #dc3545;
        background: #f8d7da;
        border-radius: 8px;
    }

    .no-orders {
        text-align: center;
        padding: 60px 40px;
    }

    .no-orders p {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 20px;
    }

    .shop-link {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .shop-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mobile-toggle {
            display: block;
        }
        
        .nav-menu {
            display: none;
            width: 100%;
            flex-direction: column;
            gap: 15px;
            padding: 20px 0;
            margin-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        .nav-menu.active {
            display: flex;
        }
        
        .nav-link {
            width: 100%;
            justify-content: center;
        }

        .card {
            margin: 20px;
            padding: 20px;
        }

        .order-table th,
        .order-table td {
            padding: 10px;
            font-size: 12px;
        }

        .detail-button {
            padding: 5px 12px;
            font-size: 10px;
        }
    }
</style>

<header class="header">
    <div class="header-container">
        <div class="logo">
            <a href="?route=products">
                <span>🛍️</span>
                <span>ShopEase</span>
            </a>
        </div>
        
        <button class="mobile-toggle" onclick="toggleMobileMenu()">☰</button>
        
        <nav class="nav-menu">
            <a href="?route=products" class="nav-link">
                <span>🏠</span> Products
            </a>
            <a href="?route=orders/my-orders" class="nav-link active">
                <span>📦</span> My Orders
            </a>
            <a href="?route=cart" class="nav-link">
                <span>🛒</span> Cart
            </a>
        </nav>
    </div>
</header>

<div class="card">
    <h2>
        <span>📦</span> My Orders
    </h2>
    
    <?php if (empty($orders)): ?>
        <div class="no-orders">
            <p>🛒 You haven't placed any orders yet.</p>
            <a href="?route=products" class="shop-link">Start Shopping →</a>
        </div>
    <?php else: ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr class="order-row">
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo date('F j, Y', strtotime($order['date'])); ?></td>
                        <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                <?php 
                                $icons = ['Delivered' => '✅', 'Processing' => '⚙️', 'Shipped' => '🚚', 'Pending' => '⏳'];
                                echo ($icons[$order['status']] ?? '📦') . ' ' . $order['status']; 
                                ?>
                            </span>
                        </td>
                        <td>
                            <button class="detail-button" onclick="showOrderDetails(<?php echo $order['id']; ?>)">
                                View Details ↓
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Order Details Modal -->
<div id="orderModal" class="order-modal">
    <div class="order-modal-content">
        <div class="order-modal-header">
            <h3>📋 Order Details</h3>
            <span class="order-modal-close" onclick="closeOrderModal()">&times;</span>
        </div>
        <div class="order-modal-body" id="orderModalBody">
            <div class="loading">Loading order details</div>
        </div>
    </div>
</div>

<script>
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    navMenu.classList.toggle('active');
}

function showOrderDetails(orderId) {
    const modal = document.getElementById('orderModal');
    const modalBody = document.getElementById('orderModalBody');
    
    // Show loading
    modalBody.innerHTML = '<div class="loading">Loading order details</div>';
    modal.style.display = 'block';
    
    // Fetch order details using the current page with ajax parameter
    fetch(`?route=orders/my-orders&ajax=order_details&order_id=${orderId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(items => {
            if (items && items.length > 0) {
                let html = '<table class="items-table">';
                html += '<thead>';
                html += '<tr>';
                html += '<th>Product</th>';
                html += '<th>Quantity</th>';
                html += '<th>Unit Price</th>';
                html += '<th>Subtotal</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                let total = 0;
                items.forEach(item => {
                    const subtotal = item.quantity * item.price;
                    total += subtotal;
                    html += '<tr>';
                    html += `<td><strong>${escapeHtml(item.product)}</strong></td>`;
                    html += `<td>✖️ ${item.quantity}</td>`;
                    html += `<td>$${item.price.toFixed(2)}</td>`;
                    html += `<td><strong>$${subtotal.toFixed(2)}</strong></td>`;
                    html += '</tr>';
                });
                
                html += '<tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">';
                html += '<td colspan="3" style="text-align: right; font-weight: bold;">🎯 Total Amount:</td>';
                html += `<td style="font-weight: bold; font-size: 1.1rem;">$${total.toFixed(2)}</td>`;
                html += '</tr>';
                
                html += '</tbody>';
                html += '</table>';
                
                // Add order summary
                html += '<div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">';
                html += '<h4>📊 Order Summary</h4>';
                html += `<p><strong>Order ID:</strong> #${orderId}</p>`;
                html += `<p><strong>Total Items:</strong> ${items.reduce((sum, item) => sum + item.quantity, 0)}</p>`;
                html += `<p><strong>Order Total:</strong> <span style="color: #007bff; font-size: 18px;">$${total.toFixed(2)}</span></p>`;
                html += '</div>';
                
                modalBody.innerHTML = html;
            } else {
                modalBody.innerHTML = '<div class="error-message">📭 No items found for this order.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<div class="error-message">❌ Error loading order details. Please try again.</div>';
        });
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.style.display = 'none';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('orderModal');
    if (event.target === modal) {
        closeOrderModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeOrderModal();
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>