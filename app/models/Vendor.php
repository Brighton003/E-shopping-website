<?php

class Vendor extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'vendors';
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }
}
