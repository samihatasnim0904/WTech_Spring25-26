<?php
class Order {
    private $db;
    
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }
    
    public function getOrdersByUser($user_id) {
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrderWithItems($order_id, $user_id = null) {
        $query = "SELECT o.*, oi.*, p.name as product_name, p.primary_image_path 
                  FROM orders o 
                  JOIN order_items oi ON o.id = oi.order_id 
                  JOIN products p ON oi.product_id = p.id 
                  WHERE o.id = :order_id";
        
        if ($user_id) {
            $query .= " AND o.user_id = :user_id";
        }
        
        $stmt = $this->db->prepare($query);
        $params = [':order_id' => $order_id];
        if ($user_id) {
            $params[':user_id'] = $user_id;
        }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllOrders($filters = []) {
        $query = "SELECT o.*, u.name as customer_name, u.email 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $query .= " AND o.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['date_from'])) {
            $query .= " AND DATE(o.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $query .= " AND DATE(o.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $query .= " ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($order_id, $status) {
        $allowed_statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        if (!in_array($status, $allowed_statuses)) {
            return false;
        }
        
        $query = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':status' => $status, ':order_id' => $order_id]);
    }
    
    public function getDeliveredOrdersWithProducts($user_id) {
        $query = "SELECT DISTINCT o.id as order_id, oi.product_id, p.name as product_name 
                  FROM orders o 
                  JOIN order_items oi ON o.id = oi.order_id 
                  JOIN products p ON oi.product_id = p.id 
                  WHERE o.user_id = :user_id AND o.status = 'Delivered'
                  ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>