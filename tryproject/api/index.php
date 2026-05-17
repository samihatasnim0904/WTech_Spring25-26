<?php
session_start();

// Fix: Simple autoloader that works
spl_autoload_register(function ($class_name) {
    // Define possible paths
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

// Load helpers - use absolute path
require_once __DIR__ . '/config/helpers.php';

// Simulate logged-in user (since Task 1 is missing)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1001;
    $_SESSION['name'] = 'Samia Hasan';
    $_SESSION['role'] = 'customer'; // Change to 'admin' by modifying this line
    $_SESSION['email'] = 'samia@example.com';
}

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path = str_replace($script_name, '', $request_uri);
$path = ltrim($path, '/');
$segments = explode('/', $path);

// Default route
$controller = isset($segments[0]) && !empty($segments[0]) ? $segments[0] : 'product';
$action = isset($segments[1]) ? $segments[1] : 'index';
$id = isset($segments[2]) ? $segments[2] : null;

// Debug: Uncomment to see routing
// echo "Controller: $controller, Action: $action, ID: $id";

// Route to appropriate controller
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