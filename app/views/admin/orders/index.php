<?php ob_start(); ?>

<div class="admin-dashboard" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2><i class="fas fa-shopping-bag" style="color:var(--primary);"></i> Manage Orders</h2>
            <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;"><?= count($allOrders) ?> total orders on the platform</p>
        </div>
        <a href="/admin/dashboard" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);">
        <table class="cart-table">
            <thead>
                <tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php foreach($allOrders as $order):
                    $statusColor = match($order['status']) {
                        'delivered' => '#28a745', 'paid' => 'var(--primary)',
                        'shipped'   => '#17a2b8', 'cancelled' => '#dc3545',
                        default     => '#6c757d'
                    };
                    $statusIcon = match($order['status']) {
                        'delivered' => 'fa-check-circle', 'paid' => 'fa-credit-card',
                        'shipped'   => 'fa-shipping-fast', 'cancelled' => 'fa-times-circle',
                        default     => 'fa-clock'
                    };
                ?>
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:var(--primary);">
                            #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                        </span>
                    </td>
                    <td style="font-weight:600;"><?= htmlspecialchars($order['user_name']) ?></td>
                    <td style="color:var(--primary);font-weight:700;">$<?= number_format($order['total_price'], 2) ?></td>
                    <td>
                        <span style="padding:0.25rem 0.7rem;border-radius:50px;font-size:0.76rem;font-weight:600;background:<?= $statusColor ?>;color:#fff;">
                            <i class="fas <?= $statusIcon ?>"></i> <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:0.85rem;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
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
