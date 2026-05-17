<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../Model/Review.php';
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$reviewModel = new Review($db);
$orderModel = new Order($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $product_id = $input['product_id'] ?? null;
    $rating = $input['rating'] ?? null;
    $review_text = trim($input['review_text'] ?? '');
    $user_id = $_SESSION['user_id'];
    
    $errors = [];
    if (!$product_id) $errors[] = 'Product ID is required';
    if (!$rating || $rating < 1 || $rating > 5) $errors[] = 'Rating must be between 1 and 5';
    if (empty($review_text)) $errors[] = 'Review text is required';
    elseif (strlen($review_text) < 5) $errors[] = 'Review must be at least 5 characters';
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['errors' => $errors]);
        exit();
    }
    
    $deliveredProducts = $orderModel->getDeliveredOrdersWithProducts($user_id);
    $hasPurchased = false;
    foreach ($deliveredProducts as $item) {
        if ($item['product_id'] == $product_id) $hasPurchased = true;
    }
    
    if (!$hasPurchased) {
        http_response_code(403);
        echo json_encode(['error' => 'You can only review products you have purchased']);
        exit();
    }
    
    if ($reviewModel->hasUserReviewed($product_id, $user_id)) {
        http_response_code(409);
        echo json_encode(['error' => 'You have already reviewed this product']);
        exit();
    }
    
    if ($reviewModel->create($product_id, $user_id, $rating, $review_text)) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to submit review']);
    }
} elseif ($method === 'GET') {
    $product_id = null;
    if (preg_match('/\/api\/products\/(\d+)\/reviews/', $_SERVER['REQUEST_URI'], $matches)) {
        $product_id = $matches[1];
    } elseif (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
    }
    
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID required']);
        exit();
    }
    
    $reviews = $reviewModel->getProductReviews($product_id);
    $ratingStats = $reviewModel->getAverageRating($product_id);
    
    echo json_encode([
        'average_rating' => $ratingStats['average'],
        'review_count' => $ratingStats['count'],
        'reviews' => $reviews
    ]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>