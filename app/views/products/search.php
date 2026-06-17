<?php ob_start(); ?>

<div style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2 class="section-title"><i class="fas fa-search" style="color:var(--primary);"></i> Search Results</h2>
            <?php if (!empty($query)): ?>
                <p style="color:var(--text-muted);">Showing results for "<strong><?= htmlspecialchars($query) ?></strong>"</p>
            <?php endif; ?>
        </div>
        
        <form action="/search" method="GET" style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <?php if(!empty($query)): ?>
                <input type="hidden" name="q" value="<?= htmlspecialchars($query) ?>">
            <?php endif; ?>
            <select name="category" class="form-control" style="width:auto;min-width:200px;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
        </form>
    </div>

    <?php if(empty($products)): ?>
        <div class="glassmorphism" style="text-align:center;padding:4rem 2rem;border-radius:var(--radius-lg);">
            <i class="fas fa-box-open" style="font-size:4rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <h3 style="margin-bottom:0.5rem;">No products found</h3>
            <p style="color:var(--text-muted);font-size:1.1rem;margin-bottom:1.5rem;">Try adjusting your search or category filter.</p>
            <a href="/" class="btn btn-primary"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php 
                        $img = $product['image'];
                        $src = $img
                            ? (str_starts_with($img,'http') ? $img : '/assets/images/'.$img)
                            : 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&q=80';
                        ?>
                        <img src="<?= $src ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="product-info">
                        <span class="category-tag"><?= htmlspecialchars($product['category_name'] ?? '') ?></span>
                        <a href="/product/<?= $product['id'] ?>" style="font-size:0.92rem;font-weight:600;line-height:1.3;">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                        <p class="vendor"><i class="fas fa-store" style="font-size:0.75rem;margin-right:3px;"></i> <?= htmlspecialchars($product['store_name']) ?></p>
                        <p class="price">$<?= number_format($product['price'], 2) ?></p>
                        <button class="btn btn-primary add-to-cart-btn" data-id="<?= $product['id'] ?>" style="width:100%;margin-top:0.6rem;">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
