<?php

class Product extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'products';
    }

    public function getFeatured($limit = 12) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, v.store_name 
                                    FROM {$this->table} p 
                                    LEFT JOIN categories c ON p.category_id = c.id
                                    LEFT JOIN vendors v ON p.vendor_id = v.id
                                    ORDER BY p.created_at DESC LIMIT :limit");
        // Bind limit specifically as integer
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithDetails($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, v.store_name 
                                    FROM {$this->table} p 
                                    LEFT JOIN categories c ON p.category_id = c.id
                                    LEFT JOIN vendors v ON p.vendor_id = v.id
                                    WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function decrementStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET stock = stock - :qty1 WHERE id = :id AND stock >= :qty2");
        return $stmt->execute(['qty1' => $quantity, 'qty2' => $quantity, 'id' => $id]);
    }
}
