<?php ob_start(); ?>

<div class="admin-dashboard" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2><i class="fas fa-store" style="color:var(--primary);"></i> Manage Vendors</h2>
            <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;">All registered vendor stores</p>
        </div>
        <a href="/admin/dashboard" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);">
        <table class="cart-table">
            <thead>
                <tr><th>ID</th><th>Store Name</th><th>Owner</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach($allVendors as $vendor): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:0.82rem;">#<?= $vendor['id'] ?></td>
                    <td style="font-weight:600;">
                        <i class="fas fa-store" style="color:var(--primary);margin-right:0.4rem;"></i>
                        <?= htmlspecialchars($vendor['store_name']) ?>
                    </td>
                    <td><?= htmlspecialchars($vendor['user_name']) ?></td>
                    <td>
                        <?php
                        $color = match($vendor['status']) {
                            'approved'  => '#28a745',
                            'suspended' => '#dc3545',
                            'rejected'  => '#6c757d',
                            default     => '#f0ad4e'
                        };
                        $icon = match($vendor['status']) {
                            'approved'  => 'fa-check-circle',
                            'suspended' => 'fa-ban',
                            'rejected'  => 'fa-times-circle',
                            default     => 'fa-clock'
                        };
                        ?>
                        <span style="padding:0.25rem 0.7rem;border-radius:50px;font-size:0.76rem;font-weight:600;background:<?= $color ?>;color:#fff;">
                            <i class="fas <?= $icon ?>"></i> <?= ucfirst($vendor['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <?php if($vendor['status'] !== 'suspended'): ?>
                                <form action="/admin/vendors/suspend/<?= $vendor['id'] ?>" method="POST" onsubmit="return confirm('Suspend this vendor?');">
                                    <button type="submit" style="padding:0.35rem 0.8rem;font-size:0.78rem;background:#f0ad4e;color:#000;border:none;border-radius:var(--radius-sm);cursor:pointer;font-weight:600;">
                                        <i class="fas fa-pause"></i> Suspend
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="/admin/vendors/restore/<?= $vendor['id'] ?>" method="POST">
                                    <button type="submit" style="padding:0.35rem 0.8rem;font-size:0.78rem;background:#28a745;color:#fff;border:none;border-radius:var(--radius-sm);cursor:pointer;font-weight:600;">
                                        <i class="fas fa-play"></i> Restore
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form action="/admin/vendors/delete/<?= $vendor['id'] ?>" method="POST" onsubmit="return confirm('Delete this vendor and all their products?');">
                                <button type="submit" class="btn btn-danger" style="padding:0.35rem 0.8rem;font-size:0.78rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
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
