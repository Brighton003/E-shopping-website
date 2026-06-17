<?php
// Initialize DB and classes
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
require_once ROOT . '/config/database.php';
require_once APP . '/core/Model.php';
require_once APP . '/models/User.php';
require_once APP . '/models/Vendor.php';
require_once APP . '/models/Category.php';
require_once APP . '/models/Product.php';

$db = Database::getInstance()->getConnection();

// Clean tables for fresh seed
$db->exec("SET FOREIGN_KEY_CHECKS = 0;");
$db->exec("TRUNCATE TABLE reviews;");
$db->exec("TRUNCATE TABLE order_items;");
$db->exec("TRUNCATE TABLE orders;");
$db->exec("TRUNCATE TABLE products;");
$db->exec("TRUNCATE TABLE categories;");
$db->exec("TRUNCATE TABLE vendors;");
$db->exec("TRUNCATE TABLE users;");
$db->exec("SET FOREIGN_KEY_CHECKS = 1;");

echo "Seeding Admin User...\n";
$userModel = new User();
$userModel->create([
    'name' => 'Admin User',
    'email' => 'admin@paulz.com',
    'password' => 'brighton',
    'role' => 'admin'
]);

echo "Seeding Vendors...\n";
$userModel->create([
    'name' => 'Tech Vendor',
    'email' => 'tech@paulz.com',
    'password' => 'brighton',
    'role' => 'vendor'
]);
$techVendorUser = $userModel->findByEmail('tech@paulz.com');

$db->prepare("INSERT INTO vendors (user_id, store_name, status) VALUES (?, ?, 'approved')")
   ->execute([$techVendorUser['id'], 'Paulz Electronics Hub']);
$vendorId = $db->lastInsertId();

echo "Seeding Categories...\n";
$categories = ['Electronics', 'Fashion', 'Home & Kitchen', 'Beauty', 'Sports'];
$catIds = [];
foreach ($categories as $cat) {
    $db->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$cat]);
    $catIds[] = $db->lastInsertId();
}

echo "Seeding Products...\n";
$products = [
    ['name' => 'Smartphone X1', 'price' => 599.99, 'stock' => 50, 'cat' => $catIds[0], 'desc' => 'High-end smartphone with a stunning display.', 'img' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&q=80'],
    ['name' => 'Wireless Headphones', 'price' => 149.50, 'stock' => 100, 'cat' => $catIds[0], 'desc' => 'Noise-cancelling wireless headphones.', 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80'],
    ['name' => 'Men\'s Running Shoes', 'price' => 89.99, 'stock' => 200, 'cat' => $catIds[1], 'desc' => 'Comfortable running shoes for all terrains.', 'img' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&q=80'],
    ['name' => 'Coffee Maker Deluxe', 'price' => 120.00, 'stock' => 30, 'cat' => $catIds[2], 'desc' => 'Brew the perfect cup every morning.', 'img' => 'https://images.unsplash.com/photo-1495474472201-4bdcd727b7b1?w=500&q=80'],
    ['name' => 'Gaming Laptop', 'price' => 1299.00, 'stock' => 15, 'cat' => $catIds[0], 'desc' => 'Powerful gaming laptop with RTX graphics.', 'img' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80'],
    ['name' => 'Luxury Perfume', 'price' => 75.00, 'stock' => 80, 'cat' => $catIds[3], 'desc' => 'A beautifully scented luxury perfume.', 'img' => 'https://images.unsplash.com/photo-1594035910387-fea477274996?w=500&q=80']
];

foreach ($products as $p) {
    $db->prepare("INSERT INTO products (vendor_id, category_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?)")
       ->execute([$vendorId, $p['cat'], $p['name'], $p['desc'], $p['price'], $p['stock'], $p['img']]);
}

echo "Database seeded successfully!\n";
