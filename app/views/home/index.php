<?php ob_start(); ?>

<!-- Hero -->
<section class="hero">
    <h1>🛍️ Welcome to <span style="color:#fff;">Paulz'</span> E-Shopping</h1>
    <p>Discover amazing products from trusted vendors across the country.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.5rem;">
            <a href="/register" class="btn btn-primary" style="font-size:1rem;padding:0.9rem 2rem;">Get Started <i class="fas fa-arrow-right"></i></a>
            <a href="/login" class="btn" style="background:rgba(255,255,255,0.15);color:#fff;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.3);font-size:1rem;padding:0.9rem 2rem;">Login</a>
        </div>
    <?php endif; ?>
</section>

<!-- Categories -->
<section class="categories-section">
    <h2 class="section-title"><i class="fas fa-th-large" style="color:var(--primary);"></i> Shop by Category</h2>
    <div class="categories-grid" style="grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;">
        <?php 
        $icons = ['Electronics'=>'fa-microchip','Fashion'=>'fa-shirt','Home & Kitchen'=>'fa-blender','Beauty'=>'fa-spa','Sports'=>'fa-running'];
        foreach($categories as $cat): 
            $icon = $icons[$cat['name']] ?? 'fa-tag';
        ?>
            <a href="/search?category=<?= $cat['id'] ?>" style="text-decoration:none;">
                <div class="category-card" style="cursor:pointer;transition:var(--transition);">
                    <i class="fas <?= $icon ?>" style="font-size:2rem;color:var(--primary);margin-bottom:0.7rem;display:block;"></i>
                    <h3 style="font-size:0.9rem;font-weight:600;color:var(--text);"><?= htmlspecialchars($cat['name']) ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products">
    <h2 class="section-title"><i class="fas fa-fire" style="color:var(--primary);"></i> Featured Products</h2>
    <div class="product-grid">
        <?php foreach($featuredProducts as $product): ?>
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
</section>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
