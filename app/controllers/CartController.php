<?php

class CartController extends Controller {
    public function index() {
        $this->view('cart/index');
    }

    public function add() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        $productId = $data['product_id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            return;
        }

        $productModel = new Product();
        $product = $productModel->findById($productId);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }

        echo json_encode(['success' => true, 'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity'))]);
    }

    public function remove() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;

        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        echo json_encode(['success' => true]);
    }
}
