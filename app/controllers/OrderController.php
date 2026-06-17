<?php

class OrderController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $orderModel = new Order();
        $orders = $orderModel->getUserOrders($_SESSION['user_id']);

        $this->view('orders/history', ['orders' => $orders]);
    }

    public function confirmDelivery($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $orderModel = new Order();
        $orders = $orderModel->getUserOrders($_SESSION['user_id']);
        
        // Make sure order belongs to user
        $belongs = false;
        foreach ($orders as $o) {
            if ($o['id'] == $id) {
                $belongs = true;
                break;
            }
        }

        if ($belongs) {
            $orderModel->updateStatus($id, 'delivered');
        }

        $this->redirect('/orders');
    }
}
