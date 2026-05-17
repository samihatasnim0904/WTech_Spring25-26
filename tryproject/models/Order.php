<?php
class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getUserOrders($userId) {
        // Garbage values - mock orders for customer
        return [
            ['id' => 105, 'user_id' => $userId, 'date' => '2025-05-18', 'total' => 49.99, 'status' => 'Processing'],
            ['id' => 102, 'user_id' => $userId, 'date' => '2025-05-10', 'total' => 89.99, 'status' => 'Shipped'],
            ['id' => 103, 'user_id' => $userId, 'date' => '2025-05-01', 'total' => 79.99, 'status' => 'Delivered'],
            ['id' => 101, 'user_id' => $userId, 'date' => '2025-04-28', 'total' => 139.98, 'status' => 'Delivered']
        ];
    }
    
    public function getAllOrders() {
        return [
            ['id' => 101, 'user_id' => 1001, 'date' => '2025-05-15', 'total' => 139.98, 'status' => 'Delivered'],
            ['id' => 102, 'user_id' => 1001, 'date' => '2025-05-10', 'total' => 89.99, 'status' => 'Shipped'],
            ['id' => 103, 'user_id' => 1001, 'date' => '2025-05-01', 'total' => 79.99, 'status' => 'Delivered'],
            ['id' => 104, 'user_id' => 1002, 'date' => '2025-05-12', 'total' => 29.99, 'status' => 'Pending'],
            ['id' => 105, 'user_id' => 1001, 'date' => '2025-05-18', 'total' => 49.99, 'status' => 'Processing']
        ];
    }
    
    public function getOrderById($id) {
        $orders = $this->getAllOrders();
        foreach ($orders as $order) {
            if ($order['id'] == $id) {
                $order['items'] = $this->getOrderItems($id);
                return $order;
            }
        }
        return null;
    }
    
    public function getOrderItems($orderId) {
        // Mock order items
        $items = [
            101 => [
                ['product_id' => 1, 'name' => 'Wireless Headphones', 'quantity' => 2, 'unit_price' => 49.99],
                ['product_id' => 3, 'name' => 'USB-C Hub', 'quantity' => 1, 'unit_price' => 29.99]
            ],
            102 => [
                ['product_id' => 2, 'name' => 'Smart Watch', 'quantity' => 1, 'unit_price' => 89.99]
            ],
            103 => [
                ['product_id' => 4, 'name' => 'Mechanical Keyboard', 'quantity' => 1, 'unit_price' => 79.99]
            ],
            105 => [
                ['product_id' => 1, 'name' => 'Wireless Headphones', 'quantity' => 1, 'unit_price' => 49.99]
            ]
        ];
        return $items[$orderId] ?? [];
    }
    
    public function updateStatus($orderId, $status) {
        // Simulate database update
        return ['ok' => true, 'message' => 'Status updated successfully'];
    }
}
?>