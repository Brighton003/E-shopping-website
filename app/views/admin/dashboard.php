<?php ob_start(); ?>

<div class="admin-dashboard" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2><i class="fas fa-chart-pie" style="color:var(--primary);"></i> Admin Dashboard</h2>
            <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;">Platform overview</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card">
            <h3><i class="fas fa-users"></i> Users</h3>
            <p><?= $stats['users'] ?></p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-store"></i> Vendors</h3>
            <p><?= $stats['vendors'] ?></p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-shopping-bag"></i> Orders</h3>
            <p><?= $stats['orders'] ?></p>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-dollar-sign"></i> Revenue</h3>
            <p>$<?= number_format($stats['revenue'], 0) ?></p>
        </div>
    </div>

    <!-- Pending Approvals -->
    <h3><i class="fas fa-clock"></i> Pending Vendor Approvals</h3>
    <?php if(empty($pendingVendors)): ?>
        <p style="color:var(--text-muted);padding:1rem 0;">✅ No pending approvals.</p>
    <?php else: ?>
        <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);margin-bottom:1rem;">
            <table class="cart-table">
                <thead><tr><th>Store</th><th>Owner</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($pendingVendors as $vendor): ?>
                    <tr>
                        <td style="font-weight:600;"><?= htmlspecialchars($vendor['store_name']) ?></td>
                        <td><?= htmlspecialchars($vendor['user_name']) ?></td>
                        <td>
                            <form action="/admin/vendors/approve/<?= $vendor['id'] ?>" method="POST" style="display:inline;">
                                <button type="submit" class="btn btn-primary" style="padding:0.4rem 0.9rem;font-size:0.8rem;">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Management Panels -->
    <h3 style="margin-top:2rem;"><i class="fas fa-cog"></i> Management Panels</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;margin-top:1rem;">
        <?php
        $panels = [
            ['icon'=>'fa-users','label'=>'Users','link'=>'/admin/users','desc'=>'Manage all customers & admins'],
            ['icon'=>'fa-store','label'=>'Vendors','link'=>'/admin/vendors','desc'=>'View, suspend or delete vendors'],
            ['icon'=>'fa-boxes','label'=>'Products','link'=>'/admin/products','desc'=>'Edit or remove any product'],
            ['icon'=>'fa-shopping-bag','label'=>'Orders','link'=>'/admin/orders','desc'=>'View all platform orders'],
        ];
        foreach($panels as $p):
        ?>
        <a href="<?= $p['link'] ?>" style="text-decoration:none;">
            <div class="stat-card" style="cursor:pointer;text-align:left;padding:1.5rem;">
                <i class="fas <?= $p['icon'] ?>" style="font-size:2rem;color:var(--primary);margin-bottom:0.7rem;display:block;"></i>
                <h3 style="font-size:1rem;color:var(--text);margin-bottom:0.3rem;"><?= $p['label'] ?></h3>
                <p style="font-size:0.82rem;color:var(--text-muted);-webkit-text-fill-color:var(--text-muted);"><?= $p['desc'] ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
