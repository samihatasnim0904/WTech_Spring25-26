<?php
class ProductController {
    private $productModel;
    private $reviewModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->reviewModel = new Review();
    }
    
    public function catalogue() {
        $products = $this->productModel->getAllProducts();
        include __DIR__ . '/../views/products/catalogue.php';
    }
    
    public function detail($productId) {
        $product = $this->productModel->getProductById($productId);
        $reviews = $this->reviewModel->getProductReviews($productId);
        $avgRating = $this->reviewModel->getAverageRating($productId);
        include __DIR__ . '/../views/products/detail.php';
    }
}
?>