<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h2>🛡️ Admin - Order Management</h2>
    
    <div class="filter-bar">
        <label>Status:
            <select id="statusFilter">
                <option value="all">All</option>
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </label>
        <label>From: <input type="date" id="dateFrom"></label>
        <label>To: <input type="date" id="dateTo"></label>
        <button id="applyFilters">Apply Filters</button>
    </div>
    
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
            <?php foreach ($orders as $order): ?>
                <tr data-order-id="<?php echo $order['id']; ?>">
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo $order['date']; ?></td>
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
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>