<?php
// File: controllers/OrderController.php

class OrderController {
    
    /**
     * Display user's orders
     * 
     * @return void
     */
    public function myOrders(): void {
        // Sample orders data
        $orders = [
            ['id' => 1001, 'date' => '2026-05-01', 'total' => 129.99, 'status' => 'Delivered'],
            ['id' => 1002, 'date' => '2026-05-10', 'total' => 89.99, 'status' => 'Processing'],
            ['id' => 1003, 'date' => '2026-05-15', 'total' => 249.99, 'status' => 'Shipped'],
        ];
        
        // Include the view
        include __DIR__ . '/../views/orders/my-orders.php';
    }
    
    /**
     * Get order details by ID
     * 
     * @param int|string $id Order ID
     * @return void
     */
    public function orderDetail(int|string $id): void {
        // Convert to int if needed
        $orderId = (int)$id;
        
        // Sample order items based on order ID
        $orderItems = [
            1001 => [
                ['product' => 'MacBook Pro', 'quantity' => 1, 'price' => 1299.99],
                ['product' => 'Mouse', 'quantity' => 2, 'price' => 29.99]
            ],
            1002 => [
                ['product' => 'iPhone 14', 'quantity' => 1, 'price' => 999.99],
                ['product' => 'Screen Protector', 'quantity' => 2, 'price' => 19.99]
            ],
            1003 => [
                ['product' => 'Sony Headphones', 'quantity' => 1, 'price' => 299.99],
                ['product' => 'Charger', 'quantity' => 1, 'price' => 49.99]
            ]
        ];
        
        $items = isset($orderItems[$orderId]) ? $orderItems[$orderId] : [];
        
        // Return as JSON for AJAX requests
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
    
    /**
     * Display admin orders panel
     * 
     * @return void
     */
    public function adminOrders(): void {
        // Sample orders data for admin with customer information
        $orders = [
            [
                'id' => 1001, 
                'date' => '2026-05-01', 
                'user_id' => 1001,
                'customer_name' => 'John Doe',
                'total' => 129.99, 
                'status' => 'Delivered'
            ],
            [
                'id' => 1002, 
                'date' => '2026-05-10', 
                'user_id' => 1002,
                'customer_name' => 'Jane Smith',
                'total' => 89.99, 
                'status' => 'Processing'
            ],
            [
                'id' => 1003, 
                'date' => '2026-05-15', 
                'user_id' => 1003,
                'customer_name' => 'Bob Johnson',
                'total' => 249.99, 
                'status' => 'Shipped'
            ],
            [
                'id' => 1004, 
                'date' => '2026-05-16', 
                'user_id' => 1001,
                'customer_name' => 'John Doe',
                'total' => 59.99, 
                'status' => 'Pending'
            ],
            [
                'id' => 1005, 
                'date' => '2026-05-17', 
                'user_id' => 1004,
                'customer_name' => 'Alice Williams',
                'total' => 199.99, 
                'status' => 'Cancelled'
            ]
        ];
        
        // Include the view - $orders will be available in the view
        include __DIR__ . '/../views/orders/admin-orders.php';
    }
    
    /**
     * Update order status via AJAX
     * 
     * @return void
     */
    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
            $newStatus = isset($_POST['status']) ? $_POST['status'] : '';
            
            // Here you would update the database
            // For demo purposes, just return success
            
            $response = [
                'success' => true,
                'message' => "Order #{$orderId} status updated to {$newStatus}",
                'order_id' => $orderId,
                'new_status' => $newStatus
            ];
            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
}
?>