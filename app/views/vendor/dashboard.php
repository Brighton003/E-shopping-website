<?php ob_start(); ?>

<div class="vendor-dashboard" style="margin-top:2rem;">
    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2><i class="fas fa-store" style="color:var(--primary);"></i> <?= htmlspecialchars($vendor['store_name']) ?></h2>
            <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;">Vendor Dashboard</p>
        </div>
        <a href="/vendor/products/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <!-- Stats -->
    <?php 
    $totalSalesItems = 0;
    $totalRevenue = 0;
    foreach($sales as $s) {
        if ($s['order_status'] !== 'cancelled') {
            $totalSalesItems += $s['quantity'];
            $totalRevenue += ($s['price'] * $s['quantity']);
        }
    }
    ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card">
            <h3><i class="fas fa-box"></i> Products</h3>
            <p><?= count($products) ?></p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-shopping-bag"></i> Items Sold</h3>
            <p><?= $totalSalesItems ?></p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-dollar-sign"></i> Revenue</h3>
            <p>$<?= number_format($totalRevenue, 2) ?></p>
        </div>
    </div>

    <!-- Recent Sales -->
    <h3><i class="fas fa-chart-line"></i> Recent Sales</h3>
    <?php if(empty($sales)): ?>
        <div style="text-align:center;padding:2.5rem;color:var(--text-muted);">
            <i class="fas fa-receipt" style="font-size:2.5rem;display:block;margin-bottom:0.8rem;"></i>
            No sales yet. Share your products to get started!
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Date</th><th>Product</th><th>Customer</th><th>Qty</th><th>Total</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sales as $sale): ?>
                    <tr>
                        <td><?= date('M d, Y', strtotime($sale['created_at'])) ?></td>
                        <td><?= htmlspecialchars($sale['product_name']) ?></td>
                        <td><?= htmlspecialchars($sale['customer_name']) ?></td>
                        <td><?= $sale['quantity'] ?></td>
                        <td style="color:var(--primary);font-weight:700;">$<?= number_format($sale['price'] * $sale['quantity'], 2) ?></td>
                        <td>
                            <?php
                            $color = match($sale['order_status']) {
                                'delivered' => '#28a745', 'paid' => 'var(--primary)',
                                'shipped'   => '#17a2b8', default => '#6c757d'
                            };
                            ?>
                            <span style="padding:0.25rem 0.7rem;border-radius:50px;font-size:0.78rem;font-weight:600;background:<?= $color ?>;color:#fff;">
                                <?= ucfirst($sale['order_status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Products -->
    <h3 style="margin-top:2rem;"><i class="fas fa-boxes"></i> Your Products</h3>
    <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);">
        <table class="cart-table">
            <thead>
                <tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): 
                    $img = $product['image'];
                    $src = $img ? (str_starts_with($img,'http') ? $img : '/assets/images/'.$img) : 'https://via.placeholder.com/50';
                ?>
                <tr>
                    <td><img src="<?= $src ?>" width="55" height="55" style="border-radius:var(--radius-sm);object-fit:cover;"></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($product['name']) ?></td>
                    <td style="color:var(--primary);font-weight:700;">$<?= number_format($product['price'], 2) ?></td>
                    <td>
                        <span style="background:rgba(246,139,30,0.15);padding:0.25rem 0.65rem;border-radius:50px;font-size:0.82rem;font-weight:600;">
                            <?= $product['stock'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="/vendor/products/edit/<?= $product['id'] ?>" class="btn btn-primary" style="padding:0.4rem 0.9rem;font-size:0.8rem;text-decoration:none;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
