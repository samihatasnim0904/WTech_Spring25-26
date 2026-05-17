<?php
// Admin Order Management Page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: /WTech_Spring25-26/Project_4_Task_04/View/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 1rem; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 1rem; }
        .page-title { margin-bottom: 2rem; color: #333; }
        .filters { background: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .filter-group label { font-weight: bold; font-size: 0.9rem; }
        .filter-group select, .filter-group input { padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
        .btn-filter { background: #007bff; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }
        .btn-filter:hover { background: #0056b3; }
        .orders-table { background: white; border-radius: 8px; overflow-x: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #f8f9fa; font-weight: bold; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; display: inline-block; margin-left: 0.5rem; }
        .status-Pending { background: #ffc107; color: #333; }
        .status-Processing { background: #17a2b8; color: white; }
        .status-Shipped { background: #007bff; color: white; }
        .status-Delivered { background: #28a745; color: white; }
        .status-Cancelled { background: #dc3545; color: white; }
        .status-select { padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid #ddd; cursor: pointer; }
        .empty-orders { text-align: center; padding: 3rem; color: #666; }
        .flash-message { position: fixed; top: 20px; right: 20px; padding: 1rem; border-radius: 4px; z-index: 1000; animation: slideIn 0.3s ease; }
        .flash-success { background: #28a745; color: white; }
        .flash-error { background: #dc3545; color: white; }
        @keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }
        @media (max-width: 768px) { th, td { padding: 0.5rem; font-size: 0.85rem; } }
        .status-select:disabled { opacity: 0.6; cursor: not-allowed; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div><h2>🛡️ Admin Dashboard</h2></div>
        <div>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/admin/dashboard.php">📊 Dashboard</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/Controller/AdminOrderController.php?action=manage">📦 Orders</a>
            <a href="/WTech_Spring25-26/Project_4_Task_04/View/logout.php">🚪 Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <h1 class="page-title">📦 Order Management</h1>
        
        <div class="filters">
            <div class="filter-group">
                <label>Status Filter</label>
                <select id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Pending">⏳ Pending</option>
                    <option value="Processing">⚙️ Processing</option>
                    <option value="Shipped">🚚 Shipped</option>
                    <option value="Delivered">✅ Delivered</option>
                    <option value="Cancelled">❌ Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Date From</label>
                <input type="date" id="dateFrom">
            </div>
            <div class="filter-group">
                <label>Date To</label>
                <input type="date" id="dateTo">
            </div>
            <div class="filter-group">
                <button class="btn-filter" onclick="applyFilters()">🔍 Apply Filters</button>
            </div>
            <div class="filter-group">
                <button class="btn-filter" onclick="resetFilters()">🔄 Reset</button>
            </div>
        </div>
        
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="empty-orders">📭 No orders found</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr data-order-id="<?php echo $order['id']; ?>" data-current-status="<?php echo $order['status']; ?>">
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['email']); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <select class="status-select" data-order-id="<?php echo $order['id']; ?>" onchange="updateOrderStatus(this)">
                                        <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                        <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>⚙️ Processing</option>
                                        <option value="Shipped" <?php echo $order['status'] == 'Shipped' ? 'selected' : ''; ?>>🚚 Shipped</option>
                                        <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>✅ Delivered</option>
                                        <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                    </select>
                                    <span class="status-badge status-<?php echo $order['status']; ?>" id="badge-<?php echo $order['id']; ?>">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="flashMessage"></div>
    
    <script>
        async function updateOrderStatus(selectElement) {
            const orderId = selectElement.dataset.orderId;
            const newStatus = selectElement.value;
            
            // Disable select during update
            selectElement.disabled = true;
            
            try {
                // ✅ Using TRUE REST API endpoint: PUT /api/orders/{id}
                const response = await fetch(`/WTech_Spring25-26/Project_4_Task_04/api/orders/${orderId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ status: newStatus })
                });
                
                const result = await response.json();
                
                if (response.ok && result.ok) {
                    // Update the badge
                    const badgeElement = document.getElementById(`badge-${orderId}`);
                    const oldStatus = badgeElement.textContent;
                    badgeElement.className = `status-badge status-${newStatus}`;
                    badgeElement.textContent = newStatus;
                    
                    // Update row data attribute
                    const row = selectElement.closest('tr');
                    row.dataset.currentStatus = newStatus;
                    
                    // Show success message (inline, not alert)
                    showFlashMessage(`✅ Order #${orderId} status updated from ${oldStatus} to ${newStatus}`, 'success');
                } else {
                    // Show error message (inline, not alert)
                    showFlashMessage(`❌ ${result.error || 'Failed to update order status'}`, 'error');
                    // Revert select to original value
                    const originalStatus = document.getElementById(`badge-${orderId}`).textContent;
                    selectElement.value = originalStatus;
                }
            } catch (error) {
                showFlashMessage(`❌ Network error: ${error.message}`, 'error');
                // Revert select to original value
                const originalStatus = document.getElementById(`badge-${orderId}`).textContent;
                selectElement.value = originalStatus;
            } finally {
                selectElement.disabled = false;
            }
        }
        
        function showFlashMessage(message, type) {
            const flashDiv = document.getElementById('flashMessage');
            flashDiv.innerHTML = `<div class="flash-message flash-${type}">${message}</div>`;
            setTimeout(() => {
                flashDiv.innerHTML = '';
            }, 3000);
        }
        
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            let url = '/WTech_Spring25-26/Project_4_Task_04/Controller/AdminOrderController.php?action=manage';
            if (status) url += `&status=${encodeURIComponent(status)}`;
            if (dateFrom) url += `&date_from=${encodeURIComponent(dateFrom)}`;
            if (dateTo) url += `&date_to=${encodeURIComponent(dateTo)}`;
            
            window.location.href = url;
        }
        
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            applyFilters();
        }
    </script>
</body>
</html>