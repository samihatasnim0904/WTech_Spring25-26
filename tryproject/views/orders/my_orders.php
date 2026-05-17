<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h2>📦 My Orders</h2>
    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr class="order-row" data-order-id="<?php echo $order['id']; ?>">
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo $order['date']; ?></td>
                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </td>
                </tr>
                <tr class="order-detail-row" id="detail-<?php echo $order['id']; ?>" style="display:none;">
                    <td colspan="4">
                        <div class="order-items" id="items-<?php echo $order['id']; ?>"></div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>