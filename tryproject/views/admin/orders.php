<?php
// Initialize $orders array to avoid the error
$orders = [];

// In a real application, you would fetch from database
// For now, using mock data for demonstration
$orders = [
    [
        'id' => 1001,
        'date' => '2024-01-15 14:30:00',
        'user_id' => 5001,
        'total' => 149.97,
        'status' => 'Delivered'
    ],
    [
        'id' => 1002,
        'date' => '2024-01-16 09:15:00',
        'user_id' => 5002,
        'total' => 89.99,
        'status' => 'Shipped'
    ],
    [
        'id' => 1003,
        'date' => '2024-01-17 16:45:00',
        'user_id' => 5003,
        'total' => 129.99,
        'status' => 'Processing'
    ],
    [
        'id' => 1004,
        'date' => '2024-01-18 11:20:00',
        'user_id' => 5004,
        'total' => 199.98,
        'status' => 'Pending'
    ],
    [
        'id' => 1005,
        'date' => '2024-01-19 13:10:00',
        'user_id' => 5005,
        'total' => 59.99,
        'status' => 'Cancelled'
    ],
    [
        'id' => 1006,
        'date' => '2024-01-20 10:00:00',
        'user_id' => 5001,
        'total' => 79.99,
        'status' => 'Processing'
    ]
];

// Handle AJAX request for updating order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $newStatus = isset($_POST['status']) ? $_POST['status'] : '';
    
    // In real application, update database here
    // For demo, just return success
    $response = [
        'success' => true,
        'message' => 'Order status updated successfully',
        'order_id' => $orderId,
        'new_status' => $newStatus
    ];
    
    echo json_encode($response);
    exit;
}

// Handle AJAX request for filtered orders
if (isset($_GET['ajax']) && $_GET['ajax'] == 'filter_orders') {
    header('Content-Type: application/json');
    
    $status = isset($_GET['status']) ? $_GET['status'] : 'all';
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    
    // Filter orders (in real app, filter from database)
    $filteredOrders = array_filter($orders, function($order) use ($status, $dateFrom, $dateTo) {
        if ($status !== 'all' && $order['status'] !== $status) {
            return false;
        }
        
        if ($dateFrom && strtotime($order['date']) < strtotime($dateFrom)) {
            return false;
        }
        
        if ($dateTo && strtotime($order['date']) > strtotime($dateTo . ' 23:59:59')) {
            return false;
        }
        
        return true;
    });
    
    echo json_encode(array_values($filteredOrders));
    exit;
}

include __DIR__ . '/../partials/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management</title>
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

        .card {
            max-width: 1400px;
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

        .filter-bar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-bar label {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-weight: 500;
            color: #555;
        }

        .filter-bar select,
        .filter-bar input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-bar button {
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-bar button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
        }

        .admin-table th,
        .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .admin-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .admin-table th:first-child {
            border-radius: 10px 0 0 0;
        }

        .admin-table th:last-child {
            border-radius: 0 10px 0 0;
        }

        .admin-table tbody tr:hover {
            background-color: #f8f9ff;
            transition: background 0.3s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .status-pending {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 3px solid #dc3545;
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

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
            border-left: 3px solid #28a745;
        }

        .status-cancelled {
            background-color: #e2e3e5;
            color: #383d41;
            border-left: 3px solid #6c757d;
        }

        .status-select {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 8px;
        }

        .update-status-btn {
            padding: 6px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .update-status-btn:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #667eea;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        /* Modal for order details */
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

        .modal-content {
            background-color: white;
            margin: 50px auto;
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

        .modal-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .modal-close:hover {
            transform: scale(1.1);
        }

        .modal-body {
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 2000;
            animation: slideInRight 0.3s ease;
        }

        .toast.show {
            visibility: visible;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .card {
                margin: 20px;
                padding: 20px;
            }

            .admin-table th,
            .admin-table td {
                padding: 10px;
                font-size: 12px;
            }

            .filter-bar {
                flex-direction: column;
            }

            .filter-bar label {
                width: 100%;
            }

            .status-select,
            .update-status-btn {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>

<div class="card">
    <h2>
        <span>🛡️</span> Admin - Order Management
    </h2>
    
    <div class="filter-bar">
        <label>
            Status:
            <select id="statusFilter">
                <option value="all">All</option>
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </label>
        <label>
            From: 
            <input type="date" id="dateFrom">
        </label>
        <label>
            To: 
            <input type="date" id="dateTo">
        </label>
        <button id="applyFilters">Apply Filters</button>
        <button id="resetFilters" style="background: #6c757d;">Reset Filters</button>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr data-order-id="<?php echo $order['id']; ?>">
                            <td>
                                <a href="#" onclick="viewOrderDetails(<?php echo $order['id']; ?>); return false;" style="color: #007bff; text-decoration: none;">
                                    #<?php echo $order['id']; ?>
                                </a>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($order['date'])); ?></td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td>$<?php echo number_format($order['total'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>" id="badge-<?php echo $order['id']; ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td>
                                <select class="status-select" data-id="<?php echo $order['id']; ?>">
                                    <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="Shipped" <?php echo $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button class="update-status-btn" data-id="<?php echo $order['id']; ?>">Update</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">No orders found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderDetailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>📋 Order Details</h3>
            <span class="modal-close" onclick="closeOrderDetailModal()">&times;</span>
        </div>
        <div class="modal-body" id="orderDetailBody">
            <div class="loading">Loading order details...</div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast"></div>

<script>
// Update order status
document.querySelectorAll('.update-status-btn').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-id');
        const select = document.querySelector(`.status-select[data-id="${orderId}"]`);
        const newStatus = select.value;
        
        // Send AJAX request to update status
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the badge
                const badge = document.getElementById(`badge-${orderId}`);
                badge.className = `status-badge status-${newStatus.toLowerCase()}`;
                badge.textContent = newStatus;
                
                // Show success message
                showToast('✅ Order status updated successfully!', 'success');
                
                // Optional: Reapply filters to refresh the list
                if (document.getElementById('applyFilters')) {
                    applyFilters();
                }
            } else {
                showToast('❌ Failed to update order status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ Error updating order status', 'error');
        });
    });
});

// Apply filters
document.getElementById('applyFilters').addEventListener('click', applyFilters);
document.getElementById('resetFilters').addEventListener('click', resetFilters);

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    // Show loading state
    const tbody = document.getElementById('ordersTableBody');
    tbody.innerHTML = '<tr><td colspan="6" class="loading">Loading...</td></tr>';
    
    // Fetch filtered orders
    fetch(`${window.location.href.split('?')[0]}?ajax=filter_orders&status=${status}&date_from=${dateFrom}&date_to=${dateTo}`)
        .then(response => response.json())
        .then(orders => {
            if (orders.length > 0) {
                let html = '';
                orders.forEach(order => {
                    html += `
                        <tr data-order-id="${order.id}">
                            <td><a href="#" onclick="viewOrderDetails(${order.id}); return false;" style="color: #007bff; text-decoration: none;">#${order.id}</a></td>
                            <td>${new Date(order.date).toLocaleDateString()} ${new Date(order.date).toLocaleTimeString()}</td>
                            <td>${order.user_id}</td>
                            <td>$${parseFloat(order.total).toFixed(2)}</td>
                            <td>
                                <span class="status-badge status-${order.status.toLowerCase()}" id="badge-${order.id}">
                                    ${order.status}
                                </span>
                            </td>
                            <td>
                                <select class="status-select" data-id="${order.id}">
                                    <option value="Pending" ${order.status == 'Pending' ? 'selected' : ''}>Pending</option>
                                    <option value="Processing" ${order.status == 'Processing' ? 'selected' : ''}>Processing</option>
                                    <option value="Shipped" ${order.status == 'Shipped' ? 'selected' : ''}>Shipped</option>
                                    <option value="Delivered" ${order.status == 'Delivered' ? 'selected' : ''}>Delivered</option>
                                    <option value="Cancelled" ${order.status == 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>
                                <button class="update-status-btn" data-id="${order.id}">Update</button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
                
                // Re-attach event listeners
                attachEventListeners();
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="no-data">No orders found matching filters</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="6" class="no-data">Error loading orders</td></tr>';
        });
}

function resetFilters() {
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    applyFilters();
}

function attachEventListeners() {
    document.querySelectorAll('.update-status-btn').forEach(button => {
        button.removeEventListener('click', updateStatusHandler);
        button.addEventListener('click', updateStatusHandler);
    });
}

function updateStatusHandler(e) {
    const button = e.currentTarget;
    const orderId = button.getAttribute('data-id');
    const select = document.querySelector(`.status-select[data-id="${orderId}"]`);
    const newStatus = select.value;
    
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `order_id=${orderId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`badge-${orderId}`);
            badge.className = `status-badge status-${newStatus.toLowerCase()}`;
            badge.textContent = newStatus;
            showToast('✅ Order status updated successfully!', 'success');
            applyFilters(); // Refresh the list
        } else {
            showToast('❌ Failed to update order status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Error updating order status', 'error');
    });
}

function viewOrderDetails(orderId) {
    const modal = document.getElementById('orderDetailModal');
    const modalBody = document.getElementById('orderDetailBody');
    
    modalBody.innerHTML = '<div class="loading">Loading order details...</div>';
    modal.style.display = 'block';
    
    // Fetch order details
    fetch(`?route=orders/detail/${orderId}`)
        .then(response => response.json())
        .then(items => {
            if (items && items.length > 0) {
                let html = '<table style="width:100%; border-collapse: collapse;">';
                html += '<thead><tr style="background: #f0f0f0;">';
                html += '<th style="padding: 10px;">Product</th>';
                html += '<th style="padding: 10px;">Quantity</th>';
                html += '<th style="padding: 10px;">Price</th>';
                html += '<th style="padding: 10px;">Subtotal</th>';
                html += '</tr></thead><tbody>';
                
                let total = 0;
                items.forEach(item => {
                    const subtotal = item.quantity * item.price;
                    total += subtotal;
                    html += `<tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">${escapeHtml(item.product)}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">${item.quantity}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">$${item.price.toFixed(2)}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">$${subtotal.toFixed(2)}</td>
                    </tr>`;
                });
                
                html += `<tr style="background: #f0f0f0; font-weight: bold;">
                    <td colspan="3" style="padding: 10px; text-align: right;">Total:</td>
                    <td style="padding: 10px;">$${total.toFixed(2)}</td>
                </tr>`;
                html += '</tbody></table>';
                
                modalBody.innerHTML = html;
            } else {
                modalBody.innerHTML = '<div class="no-data">No items found for this order</div>';
            }
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="no-data">Error loading order details</div>';
        });
}

function closeOrderDetailModal() {
    document.getElementById('orderDetailModal').style.display = 'none';
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
    toast.className = 'toast show';
    
    setTimeout(() => {
        toast.className = 'toast';
    }, 3000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('orderDetailModal');
    if (event.target === modal) {
        closeOrderDetailModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeOrderDetailModal();
    }
});

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>