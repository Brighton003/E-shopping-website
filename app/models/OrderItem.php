<?php

class OrderItem extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'order_items';
    }

    public function createItem($orderId, $productId, $quantity, $price) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        return $stmt->execute([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name, p.image 
            FROM {$this->table} oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}
