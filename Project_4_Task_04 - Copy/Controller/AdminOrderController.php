<?php
session_start();
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: /WTech_Spring25-26/Project_4_Task_04/View/login.php');
    exit();
}

class AdminOrderController {
    private $orderModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
    }
    
    public function manageOrders() {
        $filters = [];
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }
        
        $orders = $this->orderModel->getAllOrders($filters);
        include __DIR__ . '/../View/admin/orders/manage.php';
    }
}

$controller = new AdminOrderController();

if (isset($_GET['action']) && $_GET['action'] === 'manage') {
    $controller->manageOrders();
} else {
    $controller->manageOrders();
}
?>