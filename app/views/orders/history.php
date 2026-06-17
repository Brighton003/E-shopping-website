<?php ob_start(); ?>

<div style="margin-top:2rem;">
    <h2 class="section-title"><i class="fas fa-box-open" style="color:var(--primary);"></i> My Orders</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> Payment successful! Your order has been placed.
        </div>
    <?php endif; ?>

    <?php if(empty($orders)): ?>
        <div class="glassmorphism" style="text-align:center;padding:4rem 2rem;border-radius:var(--radius-lg);">
            <i class="fas fa-box-open" style="font-size:4rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <p style="color:var(--text-muted);font-size:1.1rem;margin-bottom:1.5rem;">You have no orders yet.</p>
            <a href="/" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Start Shopping</a>
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;border-radius:var(--radius-lg);border:1px solid var(--border);" class="glassmorphism">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Order ID</th><th>Date</th><th>Total</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order):
                        $statusColor = match($order['status']) {
                            'delivered' => '#28a745', 'paid' => 'var(--primary)',
                            'shipped'   => '#17a2b8', 'cancelled' => '#dc3545',
                            default     => '#6c757d'
                        };
                        $statusIcon = match($order['status']) {
                            'delivered' => 'fa-check-circle', 'paid'  => 'fa-credit-card',
                            'shipped'   => 'fa-shipping-fast','cancelled' => 'fa-times-circle',
                            default     => 'fa-clock'
                        };
                    ?>
                    <tr>
                        <td>
                            <span style="font-family:monospace;font-weight:700;color:var(--primary);">
                                #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                            </span>
                        </td>
                        <td style="color:var(--text-muted);"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                        <td style="font-weight:700;color:var(--primary);">$<?= number_format($order['total_price'], 2) ?></td>
                        <td>
                            <span style="padding:0.25rem 0.7rem;border-radius:50px;font-size:0.76rem;font-weight:600;background:<?= $statusColor ?>;color:#fff;">
                                <i class="fas <?= $statusIcon ?>"></i> <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if(in_array($order['status'], ['paid','shipped'])): ?>
                                <form action="/orders/confirm-delivery/<?= $order['id'] ?>" method="POST">
                                    <button type="submit" class="btn btn-primary" style="padding:0.4rem 0.9rem;font-size:0.8rem;">
                                        <i class="fas fa-check"></i> Confirm Delivery
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-size:0.85rem;">–</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
