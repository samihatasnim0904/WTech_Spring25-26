<?php
class Review {
    private $db;
    
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }
    
    public function create($product_id, $user_id, $rating, $review_text) {
        if ($this->hasUserReviewed($product_id, $user_id)) {
            return false;
        }
        
        $query = "INSERT INTO reviews (product_id, user_id, rating, review_text, created_at) 
                  VALUES (:product_id, :user_id, :rating, :review_text, NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':product_id' => $product_id,
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':review_text' => $review_text
        ]);
    }
    
    public function hasUserReviewed($product_id, $user_id) {
        $query = "SELECT id FROM reviews WHERE product_id = :product_id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':product_id' => $product_id, ':user_id' => $user_id]);
        return $stmt->fetch() !== false;
    }
    
    public function getProductReviews($product_id) {
        $query = "SELECT r.*, u.name as user_name, u.email 
                  FROM reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.product_id = :product_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':product_id' => $product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAverageRating($product_id) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
                  FROM reviews 
                  WHERE product_id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':product_id' => $product_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'average' => round($result['avg_rating'] ?? 0, 1),
            'count' => $result['review_count'] ?? 0
        ];
    }
    
    public function getUserProductReview($user_id, $product_id) {
        $query = "SELECT * FROM reviews WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>