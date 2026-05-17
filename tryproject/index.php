<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Simple autoloader
spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    return false;
});

require_once __DIR__ . '/config/helpers.php';

// Simulate logged-in user
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1001;
    $_SESSION['name'] = 'Samia Hasan';
    $_SESSION['role'] = 'customer'; // Change to 'admin' to see admin panel
    $_SESSION['email'] = 'samia@example.com';
}

// Get route from query string parameter
$route = isset($_GET['route']) ? $_GET['route'] : 'product/catalogue';
$segments = explode('/', $route);

$controller = isset($segments[0]) ? $segments[0] : 'product';
$action = isset($segments[1]) ? $segments[1] : 'catalogue';
$id = isset($segments[2]) ? $segments[2] : null;

try {
    switch ($controller) {
        case 'orders':
            $orderController = new OrderController();
            if ($action === 'my-orders') {
                $orderController->myOrders();
            } elseif ($action === 'detail' && $id) {
                $orderController->orderDetail($id);
            } else {
                $orderController->myOrders();
            }
            break;
            
        case 'admin':
            require_admin();
            $orderController = new OrderController();
            if ($action === 'orders') {
                $orderController->adminOrders();
            } else {
                $orderController->adminOrders();
            }
            break;
            
        case 'product':
            $productController = new ProductController();
            if ($action === 'detail' && $id) {
                $productController->detail($id);
            } else {
                $productController->catalogue();
            }
            break;
            
        case 'api':
            include __DIR__ . '/api/index.php';
            break;
            
        default:
            $productController = new ProductController();
            $productController->catalogue();
            break;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine();
}

?>