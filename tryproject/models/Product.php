<?php
class Product {
    private $db;
    private $reviewModel;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->reviewModel = new Review();
    }
    
    public function getAllProducts() {
        $products = $this->db->getMockData('products');
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