<?php
session_start();
require_once '../config/database.php';
require_once '../config/helpers.php';
require_once '../models/Order.php';
require_once '../models/Review.php';
require_once '../controllers/OrderController.php';
require_once '../controllers/ReviewController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Parse API endpoint
if (preg_match('/\/api\/orders\/(\d+)/', $request_uri, $matches)) {
    if ($method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        $orderController = new OrderController();
        $orderController->updateStatus($matches[1], $input['status'] ?? null);
    }
} elseif ($request_uri === '/api/reviews' && $method === 'POST') {
    $reviewController = new ReviewController();
    $reviewController->addReview();
} elseif (preg_match('/\/api\/products\/(\d+)\/reviews/', $request_uri, $matches) && $method === 'GET') {
    $reviewController = new ReviewController();
    $reviewController->getProductReviews($matches[1]);
} else {
    json_response(['error' => 'Endpoint not found'], 404);
}
?>