<?php

class User extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'users';
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'customer'
        ]);
    }
}
