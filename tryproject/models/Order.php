<?php
class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getUserOrders($userId) {
        $allOrders = $this->db->getMockData('orders');
        $userOrders = array_filter($allOrders, function($order) use ($userId) {
            return $order['user_id'] == $userId;
        });
        usort($userOrders, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return array_values($userOrders);
    }
    
    public function getAllOrders() {
        $orders = $this->db->getMockData('orders');
        usort($orders, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        return $orders;
    }
    
    public function getOrderById($id) {
        $orders = $this->db->getMockData('orders');
        foreach ($orders as $order) {
            if ($order['id'] == $id) {
                $order['items'] = $this->db->getOrderItems($id);
                return $order;
            }
        }
        return null;
    }
    
    public function updateStatus($orderId, $status) {
        // Simulate successful update
        return ['ok' => true, 'message' => 'Status updated successfully'];
    }
}
?>