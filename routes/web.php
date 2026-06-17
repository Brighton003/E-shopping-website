<?php

// Web Routes
Router::get('', [HomeController::class, 'index']);

// Auth Routes
Router::get('login', [AuthController::class, 'login']);
Router::post('login', [AuthController::class, 'login']);
Router::get('register', [AuthController::class, 'register']);
Router::post('register', [AuthController::class, 'register']);
Router::get('logout', [AuthController::class, 'logout']);

// Product Routes
Router::get('product/{id}', [ProductController::class, 'show']);
Router::post('products/review/{id}', [ProductController::class, 'review']);
Router::get('api/search', [ProductController::class, 'search']);
Router::get('search', [ProductController::class, 'searchPage']);

// Cart Routes
Router::get('cart', [CartController::class, 'index']);
Router::post('api/cart/add', [CartController::class, 'add']);
Router::post('api/cart/remove', [CartController::class, 'remove']);

// Vendor Routes
Router::get('vendor/dashboard', [VendorController::class, 'dashboard']);
Router::get('vendor/products/create', [VendorController::class, 'createProduct']);
Router::post('vendor/products/create', [VendorController::class, 'createProduct']);
Router::get('vendor/products/edit/{id}', [VendorController::class, 'editProduct']);
Router::post('vendor/products/edit/{id}', [VendorController::class, 'editProduct']);

// Admin Routes
Router::get('admin/dashboard', [AdminController::class, 'dashboard']);
Router::get('admin/users', [AdminController::class, 'users']);
Router::get('admin/vendors', [AdminController::class, 'vendors']);
Router::get('admin/products', [AdminController::class, 'products']);
Router::get('admin/orders', [AdminController::class, 'orders']);
Router::post('admin/vendors/approve/{id}', [AdminController::class, 'approveVendor']);
Router::post('admin/users/suspend/{id}', [AdminController::class, 'suspendUser']);
Router::post('admin/users/activate/{id}', [AdminController::class, 'activateUser']);
Router::post('admin/users/delete/{id}', [AdminController::class, 'deleteUser']);
Router::post('admin/vendors/suspend/{id}', [AdminController::class, 'suspendVendor']);
Router::post('admin/vendors/restore/{id}', [AdminController::class, 'restoreVendor']);
Router::post('admin/vendors/delete/{id}', [AdminController::class, 'deleteVendor']);
Router::post('admin/products/delete/{id}', [AdminController::class, 'deleteProduct']);
Router::get('admin/users/edit/{id}', [AdminController::class, 'editUser']);
Router::post('admin/users/edit/{id}', [AdminController::class, 'editUser']);
Router::get('admin/products/edit/{id}', [AdminController::class, 'editProduct']);
Router::post('admin/products/edit/{id}', [AdminController::class, 'editProduct']);

// Checkout & Orders Routes
Router::get('checkout', [CheckoutController::class, 'index']);
Router::post('checkout/process', [CheckoutController::class, 'process']);
Router::get('orders', [OrderController::class, 'index']);
Router::post('orders/confirm-delivery/{id}', [OrderController::class, 'confirmDelivery']);
