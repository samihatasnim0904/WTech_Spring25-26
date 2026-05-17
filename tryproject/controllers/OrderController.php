<?php
class OrderController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Order();
    }
    
    public function myOrders() {
        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getUserOrders($userId);
        include __DIR__ . '/../views/orders/my_orders.php';
    }
    
    public function orderDetail($orderId) {
        $order = $this->orderModel->getOrderById($orderId);
        header('Content-Type: application/json');
        echo json_encode($order);
        exit();
    }
    
    public function adminOrders() {
        $orders = $this->orderModel->getAllOrders();
        include __DIR__ . '/../views/admin/orders.php';
    }
    
    public function updateStatus($orderId, $status) {
        $result = $this->orderModel->updateStatus($orderId, $status);
        json_response($result);
    }
}
?>