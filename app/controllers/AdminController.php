<?php

class AdminController extends Controller {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $db = (new Model())->getConnection();

        // Get basic stats
        $usersCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $vendorsCount = $db->query("SELECT COUNT(*) FROM vendors")->fetchColumn();
        $ordersCount = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $totalRevenue = $db->query("SELECT SUM(total_price) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?: 0;

        // Get pending vendors (keep this on dashboard)
        $pendingVendors = $db->query("SELECT v.*, u.name as user_name FROM vendors v JOIN users u ON v.user_id = u.id WHERE v.status = 'pending'")->fetchAll();

        $this->view('admin/dashboard', [
            'stats' => [
                'users' => $usersCount,
                'vendors' => $vendorsCount,
                'orders' => $ordersCount,
                'revenue' => $totalRevenue
            ],
            'pendingVendors' => $pendingVendors
        ]);
    }

    public function users() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }
        $db = (new Model())->getConnection();
        $allUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
        $this->view('admin/users/index', ['allUsers' => $allUsers]);
    }

    public function vendors() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }
        $db = (new Model())->getConnection();
        $allVendors = $db->query("SELECT v.*, u.name as user_name FROM vendors v JOIN users u ON v.user_id = u.id")->fetchAll();
        $this->view('admin/vendors/index', ['allVendors' => $allVendors]);
    }

    public function products() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }
        $db = (new Model())->getConnection();
        $allProducts = $db->query("SELECT p.*, v.store_name FROM products p JOIN vendors v ON p.vendor_id = v.id ORDER BY p.created_at DESC")->fetchAll();
        $this->view('admin/products/index', ['allProducts' => $allProducts]);
    }

    public function orders() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }
        $db = (new Model())->getConnection();
        $allOrders = $db->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
        $this->view('admin/orders/index', ['allOrders' => $allOrders]);
    }

    public function approveVendor($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $db = (new Model())->getConnection();
        $stmt = $db->prepare("UPDATE vendors SET status = 'approved' WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $this->redirect('/admin/dashboard');
    }

    private function adminGuard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return false;
        }
        return true;
    }

    // ─── User Actions ────────────────────────────────────────
    public function suspendUser($id) {
        if (!$this->adminGuard()) return;
        $db = (new Model())->getConnection();
        $db->prepare("UPDATE users SET status = 'suspended' WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/users');
    }

    public function activateUser($id) {
        if (!$this->adminGuard()) return;
        $db = (new Model())->getConnection();
        $db->prepare("UPDATE users SET status = 'active' WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/users');
    }

    public function deleteUser($id) {
        if (!$this->adminGuard()) return;
        // Prevent deleting own account
        if ($id == $_SESSION['user_id']) {
            $this->redirect('/admin/users');
            return;
        }
        $db = (new Model())->getConnection();
        $db->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/users');
    }

    // ─── Vendor Actions ───────────────────────────────────────
    public function suspendVendor($id) {
        if (!$this->adminGuard()) return;
        $db = (new Model())->getConnection();
        $db->prepare("UPDATE vendors SET status = 'suspended' WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/vendors');
    }

    public function restoreVendor($id) {
        if (!$this->adminGuard()) return;
        $db = (new Model())->getConnection();
        $db->prepare("UPDATE vendors SET status = 'approved' WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/vendors');
    }

    public function deleteVendor($id) {
        if (!$this->adminGuard()) return;
        $db = (new Model())->getConnection();
        $db->prepare("DELETE FROM vendors WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/vendors');
    }

    // ─── Product Actions ──────────────────────────────────────
    public function deleteProduct($id) {
        if (!$this->adminGuard()) return;
        $db = (new Model())->getConnection();
        $db->prepare("DELETE FROM products WHERE id = :id")->execute(['id' => $id]);
        $this->redirect('/admin/products');
    }

    public function editUser($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $db = (new Model())->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'customer';

            $updateStmt = $db->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
            $updateStmt->execute([
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'id' => $id
            ]);

            $this->redirect('/admin/dashboard');
            return;
        }

        $this->view('admin/users/edit', ['user' => $user]);
    }

    public function editProduct($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $db = (new Model())->getConnection();

        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        if (!$product) {
            $this->redirect('/admin/dashboard');
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

            $this->redirect('/admin/dashboard');
            return;
        }

        $categories = (new Category())->findAll();
        $this->view('admin/products/edit', ['product' => $product, 'categories' => $categories]);
    }
}
