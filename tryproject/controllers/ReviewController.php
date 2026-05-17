<?php
class ReviewController {
    private $reviewModel;
    
    public function __construct() {
        $this->reviewModel = new Review();
    }
    
    public function addReview() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['error' => 'Method not allowed'], 405);
        }
        
        $productId = $_POST['product_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $reviewText = $_POST['review_text'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$productId || !$rating || !$reviewText || !$userId) {
            json_response(['error' => 'Missing required fields'], 400);
        }
        
        $result = $this->reviewModel->addReview($productId, $userId, $rating, $reviewText);
        json_response($result);
    }
    
    public function getProductReviews($productId) {
        $reviews = $this->reviewModel->getProductReviews($productId);
        json_response($reviews);
    }
}
?>