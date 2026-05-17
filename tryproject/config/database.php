<?php
class Database {
    private static $instance = null;
    private $mockData;
    
    private function __construct() {
        // Initialize mock data
        $this->mockData = [
            'orders' => [
                ['id' => 101, 'user_id' => 1001, 'date' => '2025-05-15', 'total' => 139.98, 'status' => 'Delivered'],
                ['id' => 102, 'user_id' => 1001, 'date' => '2025-05-10', 'total' => 89.99, 'status' => 'Shipped'],
                ['id' => 103, 'user_id' => 1001, 'date' => '2025-05-01', 'total' => 79.99, 'status' => 'Delivered'],
                ['id' => 104, 'user_id' => 1002, 'date' => '2025-05-12', 'total' => 29.99, 'status' => 'Pending'],
                ['id' => 105, 'user_id' => 1001, 'date' => '2025-05-18', 'total' => 49.99, 'status' => 'Processing'],
                ['id' => 106, 'user_id' => 1001, 'date' => '2025-05-20', 'total' => 119.97, 'status' => 'Delivered']
            ],
            'order_items' => [
                101 => [['product_id' => 1, 'name' => 'Wireless Headphones', 'quantity' => 2, 'unit_price' => 49.99]],
                102 => [['product_id' => 2, 'name' => 'Smart Watch', 'quantity' => 1, 'unit_price' => 89.99]],
                103 => [['product_id' => 4, 'name' => 'Mechanical Keyboard', 'quantity' => 1, 'unit_price' => 79.99]],
                105 => [['product_id' => 1, 'name' => 'Wireless Headphones', 'quantity' => 1, 'unit_price' => 49.99]],
                106 => [['product_id' => 2, 'name' => 'Smart Watch', 'quantity' => 1, 'unit_price' => 89.99], ['product_id' => 3, 'name' => 'USB-C Hub', 'quantity' => 1, 'unit_price' => 29.99]]
            ],
            'products' => [
                ['id' => 1, 'name' => 'Wireless Headphones', 'price' => 49.99, 'stock' => 12, 'image' => '🎧', 'description' => 'High-quality wireless headphones with noise cancellation'],
                ['id' => 2, 'name' => 'Smart Watch', 'price' => 89.99, 'stock' => 5, 'image' => '⌚', 'description' => 'Fitness tracker with heart rate monitor'],
                ['id' => 3, 'name' => 'USB-C Hub', 'price' => 29.99, 'stock' => 3, 'image' => '🔌', 'description' => '7-in-1 USB-C multiport adapter'],
                ['id' => 4, 'name' => 'Mechanical Keyboard', 'price' => 79.99, 'stock' => 7, 'image' => '⌨️', 'description' => 'RGB mechanical gaming keyboard']
            ]
        ];
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getMockData($key) {
        return isset($this->mockData[$key]) ? $this->mockData[$key] : [];
    }
    
    public function getOrderItems($orderId) {
        return isset($this->mockData['order_items'][$orderId]) ? $this->mockData['order_items'][$orderId] : [];
    }
}
?>