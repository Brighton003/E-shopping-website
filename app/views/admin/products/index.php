<?php ob_start(); ?>

<div class="admin-dashboard" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2><i class="fas fa-boxes" style="color:var(--primary);"></i> Manage Products</h2>
            <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;"><?= count($allProducts) ?> products across all vendors</p>
        </div>
        <a href="/admin/dashboard" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);">
        <table class="cart-table">
            <thead>
                <tr><th>#</th><th>Image</th><th>Product</th><th>Vendor</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach($allProducts as $product):
                    $img = $product['image'];
                    $src = $img ? (str_starts_with($img,'http') ? $img : '/assets/images/'.$img) : 'https://via.placeholder.com/55';
                ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:0.82rem;"><?= $product['id'] ?></td>
                    <td><img src="<?= $src ?>" width="55" height="55" style="border-radius:var(--radius-sm);object-fit:cover;"></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($product['name']) ?></td>
                    <td>
                        <span style="color:var(--text-muted);font-size:0.85rem;">
                            <i class="fas fa-store" style="font-size:0.75rem;color:var(--primary);"></i>
                            <?= htmlspecialchars($product['store_name']) ?>
                        </span>
                    </td>
                    <td style="color:var(--primary);font-weight:700;">$<?= number_format($product['price'], 2) ?></td>
                    <td>
                        <span style="padding:0.25rem 0.65rem;border-radius:50px;font-size:0.8rem;font-weight:600;background:<?= $product['stock'] > 10 ? 'rgba(40,167,69,0.2)' : 'rgba(220,53,69,0.2)' ?>;color:<?= $product['stock'] > 10 ? '#71d888' : '#ff8a9a' ?>;">
                            <?= $product['stock'] ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;">
                            <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn btn-primary" style="padding:0.35rem 0.8rem;font-size:0.78rem;text-decoration:none;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/admin/products/delete/<?= $product['id'] ?>" method="POST" onsubmit="return confirm('Delete this product permanently?');">
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
