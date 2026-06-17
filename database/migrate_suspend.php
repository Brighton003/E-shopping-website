<?php
require_once 'config/database.php';
$db = Database::getInstance()->getConnection();

try {
    $db->exec("ALTER TABLE users ADD COLUMN status ENUM('active','suspended') DEFAULT 'active'");
    echo "users.status column added\n";
} catch(Exception $e) {
    echo "users.status: " . $e->getMessage() . "\n";
}

try {
    $db->exec("ALTER TABLE vendors MODIFY status ENUM('pending','approved','rejected','suspended') DEFAULT 'pending'");
    echo "vendors.status enum updated\n";
} catch(Exception $e) {
    echo "vendors.status: " . $e->getMessage() . "\n";
}

echo "Done.\n";
