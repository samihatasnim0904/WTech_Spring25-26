<?php
session_start();

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = '';
    $base_dir = __DIR__ . '/';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

require_once 'config/helpers.php';

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($script_name, '', $request_uri);
$path = trim($path, '/');
$segments = explode('/', $path);

// Default user (simulated from Task 1)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1001;
    $_SESSION['name'] = 'Samia Hasan';
    $_SESSION['role'] = 'customer'; // Change to 'admin' for admin view
}

$controller = $segments[0] ?: 'product';
$action = $segments[1] ?? 'index';
$id = $segments[2] ?? null;

// Route to appropriate controller
switch ($controller) {
    case 'orders':
        $orderController = new OrderController();
        if ($action === 'my-orders') {
            $orderController->myOrders();
        } elseif ($action === 'detail') {
            $orderController->orderDetail($id);
        }
        break;
    case 'admin':
        require_admin();
        $orderController = new OrderController();
        if ($action === 'orders') {
            $orderController->adminOrders();
        }
        break;
    case 'product':
        $productController = new ProductController();
        if ($action === 'detail') {
            $productController->detail($id);
        } else {
            $productController->catalogue();
        }
        break;
    case 'api':
        require_once 'api/index.php';
        break;
    default:
        $productController = new ProductController();
        $productController->catalogue();
        break;
}
?>