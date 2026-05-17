<?php
session_start();
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../Model/OrderItem.php';
require_once __DIR__ . '/../Model/Review.php';
require_once __DIR__ . '/../config/database.php';

class OrderController {
    private $orderModel;
    private $reviewModel;
    private $db;
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WTech_Spring25-26/Project_4_Task_04/View/login.php');
            exit();
        }
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
        $this->reviewModel = new Review($this->db);
    }
    
    public function myOrders() {
        $user_id = $_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersByUser($user_id);
        include __DIR__ . '/../View/orders/my_orders.php';
    }
    
    public function getOrderDetails() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['order_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Order ID required']);
            return;
        }
        
        $user_id = $_SESSION['user_id'];
        $order_id = $_GET['order_id'];
        
        $orderItems = $this->orderModel->getOrderWithItems($order_id, $user_id);
        
        if (empty($orderItems)) {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
            return;
        }
        
        $order = [
            'id' => $orderItems[0]['id'],
            'total_amount' => $orderItems[0]['total_amount'],
            'status' => $orderItems[0]['status'],
            'shipping_address' => $orderItems[0]['shipping_address'],
            'payment_method' => $orderItems[0]['payment_method'],
            'created_at' => $orderItems[0]['created_at'],
            'items' => []
        ];
        
        foreach ($orderItems as $item) {
            $hasReviewed = $this->reviewModel->hasUserReviewed($item['product_id'], $user_id);
            $order['items'][] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'primary_image_path' => $item['primary_image_path'],
                'can_review' => ($order['status'] == 'Delivered' && !$hasReviewed)
            ];
        }
        
        echo json_encode($order);
    }
}

if (isset($_GET['action'])) {
    $controller = new OrderController();
    switch ($_GET['action']) {
        case 'my-orders':
            $controller->myOrders();
            break;
        case 'get-details':
            $controller->getOrderDetails();
            break;
        default:
            http_response_code(404);
            echo "Page not found";
    }
} else {
    $controller = new OrderController();
    $controller->myOrders();
}
?>