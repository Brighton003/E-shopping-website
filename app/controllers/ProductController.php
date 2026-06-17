<?php

class ProductController extends Controller {
    public function show($id) {
        $productModel = new Product();
        $product = $productModel->getWithDetails($id);

        if (!$product) {
            $this->redirect('/');
            return;
        }

        // Fetch reviews
        $stmt = (new Model())->getConnection()->prepare("
            SELECT r.*, u.name as user_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = :product_id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute(['product_id' => $id]);
        $reviews = $stmt->fetchAll();

        // Calculate average rating
        $avgRating = 0;
        if (count($reviews) > 0) {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $avgRating = round($totalRating / count($reviews), 1);
        }

        // Check if user can review (must be logged in and have ordered the product)
        $canReview = false;
        if (isset($_SESSION['user_id'])) {
            $db = (new Model())->getConnection();
            $stmt = $db->prepare("
                SELECT COUNT(*) FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = :user_id 
                  AND oi.product_id = :product_id 
                  AND o.status IN ('paid', 'shipped', 'delivered')
            ");
            $stmt->execute(['user_id' => $_SESSION['user_id'], 'product_id' => $id]);
            $canReview = $stmt->fetchColumn() > 0;
        }

        $this->view('products/show', [
            'product' => $product,
            'reviews' => $reviews,
            'avgRating' => $avgRating,
            'totalReviews' => count($reviews),
            'canReview' => $canReview
        ]);
    }

    public function review($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = (int)($_POST['rating'] ?? 0);
            $comment = $_POST['comment'] ?? '';
            $userId = $_SESSION['user_id'];

            // Double check on server side that they actually bought it
            $db = (new Model())->getConnection();
            $stmt = $db->prepare("
                SELECT COUNT(*) FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = :user_id 
                  AND oi.product_id = :product_id 
                  AND o.status IN ('paid', 'shipped', 'delivered')
            ");
            $stmt->execute(['user_id' => $userId, 'product_id' => $id]);
            $canReview = $stmt->fetchColumn() > 0;

            if ($canReview && $rating >= 1 && $rating <= 5) {
                $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)");
                $stmt->execute([
                    'user_id' => $userId,
                    'product_id' => $id,
                    'rating' => $rating,
                    'comment' => $comment
                ]);
            }
        }

        $this->redirect("/product/{$id}");
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        if (empty($query)) {
            echo json_encode([]);
            return;
        }

        // Simple AJAX search returning JSON
        $db = (new Model())->getConnection();
        $stmt = $db->prepare("SELECT id, name, price, image FROM products WHERE name LIKE :q OR description LIKE :q LIMIT 10");
        $stmt->execute(['q' => "%$query%"]);
        $results = $stmt->fetchAll();

        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function searchPage() {
        $query = $_GET['q'] ?? '';
        $categoryId = $_GET['category'] ?? '';

        $db = (new Model())->getConnection();
        
        $sql = "SELECT p.*, v.store_name, c.name as category_name 
                FROM products p 
                JOIN vendors v ON p.vendor_id = v.id 
                JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (p.name LIKE :q OR p.description LIKE :q)";
            $params['q'] = "%$query%";
        }

        if (!empty($categoryId)) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        // Get categories for filter/display
        $catStmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $this->view('products/search', [
            'products' => $products,
            'query' => $query,
            'categoryId' => $categoryId,
            'categories' => $categories
        ]);
    }
}
