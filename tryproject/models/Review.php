<?php
class Review {
    private static $reviews = [
        ['id' => 1, 'product_id' => 1, 'user_id' => 1001, 'rating' => 5, 'review_text' => 'Amazing sound quality!', 'created_at' => '2025-05-16'],
        ['id' => 2, 'product_id' => 1, 'user_id' => 1002, 'rating' => 4, 'review_text' => 'Good but bass heavy', 'created_at' => '2025-05-14'],
        ['id' => 3, 'product_id' => 2, 'user_id' => 1001, 'rating' => 4, 'review_text' => 'Nice fitness tracker', 'created_at' => '2025-05-11'],
        ['id' => 4, 'product_id' => 4, 'user_id' => 1001, 'rating' => 5, 'review_text' => 'Perfect tactile feel', 'created_at' => '2025-05-02']
    ];
    
    public function getProductReviews($productId) {
        $result = [];
        foreach (self::$reviews as $review) {
            if ($review['product_id'] == $productId) {
                $result[] = $review;
            }
        }
        return array_reverse($result);
    }
    
    public function addReview($productId, $userId, $rating, $reviewText) {
        // Check unique constraint
        foreach (self::$reviews as $review) {
            if ($review['product_id'] == $productId && $review['user_id'] == $userId) {
                return ['error' => 'You have already reviewed this product'];
            }
        }
        
        $newReview = [
            'id' => count(self::$reviews) + 1,
            'product_id' => (int)$productId,
            'user_id' => (int)$userId,
            'rating' => (int)$rating,
            'review_text' => htmlspecialchars($reviewText),
            'created_at' => date('Y-m-d')
        ];
        self::$reviews[] = $newReview;
        return ['ok' => true, 'review' => $newReview];
    }
    
    public function getAverageRating($productId) {
        $reviews = $this->getProductReviews($productId);
        if (count($reviews) === 0) return 0;
        $sum = array_sum(array_column($reviews, 'rating'));
        return round($sum / count($reviews), 1);
    }
}
?>