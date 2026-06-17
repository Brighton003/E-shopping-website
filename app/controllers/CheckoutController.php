<?php

class CheckoutController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        if (empty($_SESSION['cart'])) {
            $this->redirect('/cart');
            return;
        }

        $this->view('checkout/index');
    }

    public function process() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            $this->redirect('/');
            return;
        }

        $phone = $_POST['phone'] ?? '';
        $network = $_POST['network'] ?? 'MTN'; // MTN or Airtel

        // Calculate total
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // 1. Create Order
        $orderModel = new Order();
        $orderId = $orderModel->createOrder($_SESSION['user_id'], $totalPrice);

        // 2. Create Order Items
        $orderItemModel = new OrderItem();
        foreach ($_SESSION['cart'] as $productId => $item) {
            $orderItemModel->createItem($orderId, $productId, $item['quantity'], $item['price']);
        }

        // 3. Simulate Mobile Money Payment Integration (MTN/Airtel)
        // In a real scenario, you would make a curl request to Flutterwave or MTN/Airtel API here
        $paymentSuccess = $this->simulateMobileMoneyPayment($phone, $network, $totalPrice);

        if ($paymentSuccess) {
            $orderModel->updateStatus($orderId, 'paid');
            
            // Decrement stock for each item
            $productModel = new Product();
            foreach ($_SESSION['cart'] as $productId => $item) {
                $productModel->decrementStock($productId, $item['quantity']);
            }
            
            // Clear cart
            unset($_SESSION['cart']);
            $this->redirect('/orders?status=success');
        } else {
            $orderModel->updateStatus($orderId, 'cancelled');
            $this->redirect('/orders?status=failed');
        }
    }

    private function simulateMobileMoneyPayment($phone, $network, $amount) {
        // Mocking an API call
        // E.g., validating phone number prefix (MTN 077/078, Airtel 070/075)
        // We'll just sleep for 1 second to simulate network delay
        sleep(1);
        return true; // Assume success for demo
    }
}
