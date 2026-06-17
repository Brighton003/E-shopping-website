<?php ob_start(); ?>

<div style="margin-top:2rem;">
    <h2 class="section-title"><i class="fas fa-shopping-cart" style="color:var(--primary);"></i> Your Shopping Cart</h2>

    <div id="cart-items">
        <?php if(empty($_SESSION['cart'])): ?>
            <div class="glassmorphism" style="text-align:center;padding:4rem 2rem;border-radius:var(--radius-lg);">
                <i class="fas fa-cart-arrow-down" style="font-size:4rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
                <p style="color:var(--text-muted);font-size:1.1rem;margin-bottom:1.5rem;">Your cart is empty.</p>
                <a href="/" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="glassmorphism" style="border-radius:var(--radius-lg);overflow:hidden;padding:0;">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grandTotal = 0;
                        foreach($_SESSION['cart'] as $id => $item): 
                            $total = $item['price'] * $item['quantity'];
                            $grandTotal += $total;
                            $img = $item['image'] ?? null;
                            $src = $img ? (str_starts_with($img,'http') ? $img : '/assets/images/'.$img) : 'https://via.placeholder.com/60';
                        ?>
                        <tr id="cart-row-<?= $id ?>">
                            <td>
                                <div style="display:flex;align-items:center;gap:1rem;">
                                    <img src="<?= $src ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="60" height="60" style="border-radius:var(--radius-sm);object-fit:cover;">
                                    <span style="font-weight:600;"><?= htmlspecialchars($item['name']) ?></span>
                                </div>
                            </td>
                            <td style="color:var(--primary);font-weight:600;">$<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <span style="background:rgba(246,139,30,0.15);padding:0.3rem 0.8rem;border-radius:50px;font-weight:600;">
                                    <?= $item['quantity'] ?>
                                </span>
                            </td>
                            <td style="font-weight:700;color:var(--primary);">$<?= number_format($total, 2) ?></td>
                            <td>
                                <button class="btn btn-danger remove-item-btn" data-id="<?= $id ?>" style="padding:0.4rem 0.8rem;font-size:0.8rem;">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top:2rem;display:flex;justify-content:flex-end;">
                <div class="glassmorphism" style="padding:2rem;min-width:320px;border-radius:var(--radius-lg);">
                    <h3 style="font-size:1rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:1rem;">Order Summary</h3>
                    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;">
                        <span>Subtotal</span>
                        <span>$<span id="grand-total"><?= number_format($grandTotal, 2) ?></span></span>
                    </div>
                    <div style="border-top:1px solid var(--border);padding-top:1rem;margin-top:0.5rem;">
                        <div style="display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;margin-bottom:1.5rem;">
                            <span>Total</span>
                            <span style="color:var(--primary);">$<?= number_format($grandTotal, 2) ?></span>
                        </div>
                        <a href="/checkout" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
