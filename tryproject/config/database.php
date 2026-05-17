<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        // Simulated database connection with garbage values
        $this->conn = true; // Mock connection
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    // Mock query method for demonstration
    public function query($sql, $params = []) {
        // Return mock data based on SQL
        return $this->getMockData($sql);
    }
    
    private function getMockData($sql) {
        // Garbage values database simulation
        $mockOrders = [
            ['id' => 101, 'user_id' => 1001, 'date' => '2025-05-15', 'total' => 139.98, 'status' => 'Delivered'],
            ['id' => 102, 'user_id' => 1001, 'date' => '2025-05-10', 'total' => 89.99, 'status' => 'Shipped'],
            ['id' => 103, 'user_id' => 1001, 'date' => '2025-05-01', 'total' => 79.99, 'status' => 'Delivered'],
            ['id' => 104, 'user_id' => 1002, 'date' => '2025-05-12', 'total' => 29.99, 'status' => 'Pending'],
            ['id' => 105, 'user_id' => 1001, 'date' => '2025-05-18', 'total' => 49.99, 'status' => 'Processing']
        ];
        
        $mockProducts = [
            ['id' => 1, 'name' => 'Wireless Headphones', 'price' => 49.99, 'avg_rating' => 4.5],
            ['id' => 2, 'name' => 'Smart Watch', 'price' => 89.99, 'avg_rating' => 4.2],
            ['id' => 3, 'name' => 'USB-C Hub', 'price' => 29.99, 'avg_rating' => 4.8],
            ['id' => 4, 'name' => 'Mechanical Keyboard', 'price' => 79.99, 'avg_rating' => 4.0]
        ];
        
        if (strpos($sql, 'orders') !== false) {
            return $mockOrders;
        } elseif (strpos($sql, 'products') !== false) {
            return $mockProducts;
        }
        return [];
    }
}
?>