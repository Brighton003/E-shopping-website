<?php

class Order extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'orders';
    }

    public function createOrder($userId, $totalPrice) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, total_price, status) VALUES (:user_id, :total_price, 'pending')");
        $stmt->execute([
            'user_id' => $userId,
            'total_price' => $totalPrice
        ]);
        return $this->db->lastInsertId();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }

    public function getUserOrders($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
