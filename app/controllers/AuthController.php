<?php

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    $this->redirect('/admin/dashboard');
                } elseif ($user['role'] === 'vendor') {
                    $this->redirect('/vendor/dashboard');
                } else {
                    $this->redirect('/');
                }
            } else {
                $error = "Invalid credentials";
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? 'customer' // Optional: allow registering as vendor
            ];

            // Basic validation
            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                $error = "Please fill in all fields.";
                $this->view('auth/register', ['error' => $error]);
                return;
            }

            $userModel = new User();
            if ($userModel->findByEmail($data['email'])) {
                $error = "Email already in use.";
                $this->view('auth/register', ['error' => $error]);
                return;
            }

            if ($userModel->create($data)) {
                $this->redirect('/login');
            } else {
                $error = "Registration failed. Please try again.";
                $this->view('auth/register', ['error' => $error]);
            }
        } else {
            $this->view('auth/register');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
