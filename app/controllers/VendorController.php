<?php

class VendorController extends Controller {
    public function dashboard() {
        // Ensure vendor is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
            $this->redirect('/login');
            return;
        }

        $vendorModel = new Vendor();
        $vendor = $vendorModel->findByUserId($_SESSION['user_id']);

        if (!$vendor) {
            // Vendor profile not set up yet
            // Normally you would show a setup page, for now we just show a message or create a dummy one
            echo "Vendor profile not setup.";
            return;
        }

        $db = (new Model())->getConnection();
        
        // Fetch products
        $stmt = $db->prepare("SELECT * FROM products WHERE vendor_id = :vendor_id ORDER BY created_at DESC");
        $stmt->execute(['vendor_id' => $vendor['id']]);
        $products = $stmt->fetchAll();

        // Fetch recent sales for this vendor
        $salesStmt = $db->prepare("
            SELECT oi.*, p.name as product_name, o.created_at, o.status as order_status, u.name as customer_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            JOIN users u ON o.user_id = u.id
            WHERE p.vendor_id = :vendor_id
            ORDER BY o.created_at DESC
        ");
        $salesStmt->execute(['vendor_id' => $vendor['id']]);
        $sales = $salesStmt->fetchAll();

        $this->view('vendor/dashboard', [
            'vendor' => $vendor,
            'products' => $products,
            'sales' => $sales
        ]);
    }

    public function createProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
            $this->redirect('/login');
            return;
        }

        $db = (new Model())->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;

            $vendor = (new Vendor())->findByUserId($_SESSION['user_id']);

            // Simple image upload
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('prod_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/assets/images/' . $imageName);
            }

            $stmt = $db->prepare("INSERT INTO products (vendor_id, category_id, name, description, price, stock, image) VALUES (:vendor_id, :category_id, :name, :description, :price, :stock, :image)");
            $stmt->execute([
                'vendor_id' => $vendor['id'],
                'category_id' => $categoryId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $imageName
            ]);

            $this->redirect('/vendor/dashboard');
            return;
        }

        // Fetch categories for dropdown
        $categories = (new Category())->findAll();
        $this->view('vendor/products/create', ['categories' => $categories]);
    }

    public function editProduct($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
            $this->redirect('/login');
            return;
        }

        $db = (new Model())->getConnection();
        $vendor = (new Vendor())->findByUserId($_SESSION['user_id']);

        // Check if product exists and belongs to this vendor
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id AND vendor_id = :vendor_id");
        $stmt->execute(['id' => $id, 'vendor_id' => $vendor['id']]);
        $product = $stmt->fetch();

        if (!$product) {
            $this->redirect('/vendor/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;

            $imageName = $product['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('prod_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/assets/images/' . $imageName);
            }

            $updateStmt = $db->prepare("UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock, image = :image WHERE id = :id");
            $updateStmt->execute([
                'category_id' => $categoryId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $imageName,
                'id' => $id
            ]);

            $this->redirect('/vendor/dashboard');
            return;
        }

        $categories = (new Category())->findAll();
        $this->view('vendor/products/edit', ['product' => $product, 'categories' => $categories]);
    }
}
