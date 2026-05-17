<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use PUT.']);
    exit();
}

$order_id = null;
if (preg_match('/\/api\/orders\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $order_id = $matches[1];
}

if (!$order_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Order ID required']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Status required']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);

$orders = $orderModel->getAllOrders(['id' => $order_id]);
if (empty($orders)) {
    http_response_code(404);
    echo json_encode(['error' => 'Order not found']);
    exit();
}

$current_status = $orders[0]['status'];
$new_status = $input['status'];

$allowed_transitions = [
    'Pending' => ['Processing', 'Cancelled'],
    'Processing' => ['Shipped', 'Cancelled'],
    'Shipped' => ['Delivered', 'Cancelled'],
    'Delivered' => ['Cancelled'],
    'Cancelled' => []
];

if (!in_array($new_status, $allowed_transitions[$current_status])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid status transition']);
    exit();
}

if ($orderModel->updateStatus($order_id, $new_status)) {
    echo json_encode(['ok' => true, 'message' => 'Order status updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update order status']);
}
?>