<?php
class Product {
    private $reviewModel;
    
    public function __construct() {
        $this->reviewModel = new Review();
    }
    
    public function getAllProducts() {
        $products = [
            ['id' => 1, 'name' => 'Wireless Headphones', 'price' => 49.99, 'stock' => 12, 'image' => '🎧', 'description' => 'High-quality wireless headphones with noise cancellation'],
            ['id' => 2, 'name' => 'Smart Watch', 'price' => 89.99, 'stock' => 5, 'image' => '⌚', 'description' => 'Fitness tracker with heart rate monitor'],
            ['id' => 3, 'name' => 'USB-C Hub', 'price' => 29.99, 'stock' => 3, 'image' => '🔌', 'description' => '7-in-1 USB-C multiport adapter'],
            ['id' => 4, 'name' => 'Mechanical Keyboard', 'price' => 79.99, 'stock' => 7, 'image' => '⌨️', 'description' => 'RGB mechanical gaming keyboard']
        ];
        
        // Add average rating to each product
        foreach ($products as &$product) {
            $product['avg_rating'] = $this->reviewModel->getAverageRating($product['id']);
        }
        
        return $products;
    }
    
    public function getProductById($id) {
        $products = $this->getAllProducts();
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }
        return null;
    }
}
?>