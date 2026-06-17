<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read the schema
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // Execute
    $pdo->exec($sql);
    echo "Database setup completed successfully.\n";
} catch (PDOException $e) {
    echo "Setup failed: " . $e->getMessage() . "\n";
}
