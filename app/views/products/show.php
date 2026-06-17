<?php ob_start(); ?>

<div class="product-details glassmorphism">
    <div class="product-image-large">
        <img src="<?= $product['image'] ? '/assets/images/'.$product['image'] : 'https://via.placeholder.com/400' ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product-info-large">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        
        <div style="margin: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
            <div style="color: #ffb74d; font-size: 1.1rem;">
                <?php for($i=1; $i<=5; $i++): ?>
                    <i class="fa<?= $i <= round($avgRating) ? 's' : 'r' ?> fa-star"></i>
                <?php endfor; ?>
            </div>
            <span style="color: var(--text-muted); font-size: 0.9rem;">(<?= $avgRating ?>/5 based on <?= $totalReviews ?> reviews)</span>
        </div>

        <p class="price-large">$<?= number_format($product['price'], 2) ?></p>
        <p class="vendor">Sold by: <strong><?= htmlspecialchars($product['store_name']) ?></strong></p>
        
        <p style="margin: 1rem 0; font-weight: 600;">
            Status: 
            <?php if($product['stock'] > 0): ?>
                <span style="color: #28a745;"><i class="fas fa-check-circle"></i> In Stock (<?= $product['stock'] ?> available)</span>
            <?php else: ?>
                <span style="color: #dc3545;"><i class="fas fa-times-circle"></i> Out of Stock</span>
            <?php endif; ?>
        </p>

        <div class="description">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>

        <?php if($product['stock'] > 0): ?>
        <div class="actions">
            <input type="number" id="qty-<?= $product['id'] ?>" value="1" min="1" max="<?= $product['stock'] ?>" class="form-control" style="width: 80px; display: inline-block;">
            <button class="btn btn-primary add-to-cart-btn" data-id="<?= $product['id'] ?>">Add to Cart</button>
        </div>
        <?php else: ?>
        <div class="actions">
            <button class="btn" disabled style="background: var(--border); color: var(--text-muted); cursor: not-allowed;">Out of Stock</button>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="reviews-section" style="margin-top: 2rem;">
    <h2 class="section-title"><i class="fas fa-star" style="color: var(--primary);"></i> Customer Reviews</h2>
    
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        
        <!-- Review List -->
        <div class="glassmorphism" style="border-radius: var(--radius-lg);">
            <?php if(empty($reviews)): ?>
                <div style="text-align:center; padding: 2rem 1rem; color: var(--text-muted);">
                    <i class="far fa-comment-dots" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach($reviews as $review): ?>
                        <div style="padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <strong><i class="fas fa-user-circle" style="color: var(--primary);"></i> <?= htmlspecialchars($review['user_name']) ?></strong>
                                <span style="color: var(--text-muted); font-size: 0.85rem;"><?= date('M d, Y', strtotime($review['created_at'])) ?></span>
                            </div>
                            <div style="color: #ffb74d; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="fa<?= $i <= $review['rating'] ? 's' : 'r' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p style="color: var(--text); font-size: 0.95rem; line-height: 1.5;"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Review Form -->
        <div class="glassmorphism" style="border-radius: var(--radius-lg);">
            <h3 style="margin-bottom: 1rem;">Write a Review</h3>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if(isset($canReview) && $canReview): ?>
                    <form action="/products/review/<?= $product['id'] ?>" method="POST">
                        <div class="form-group">
                            <label>Your Rating</label>
                            <select name="rating" class="form-control" required style="width: 150px;">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                <option value="3">⭐⭐⭐ (3/5)</option>
                                <option value="2">⭐⭐ (2/5)</option>
                                <option value="1">⭐ (1/5)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Your Review</label>
                            <textarea name="comment" class="form-control" rows="4" required placeholder="What did you like or dislike?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Review</button>
                    </form>
                <?php else: ?>
                    <div style="padding: 1.5rem; border: 1px solid var(--border); border-radius: var(--radius-sm); text-align: center;">
                        <i class="fas fa-lock" style="font-size: 2rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
                        <p style="color: var(--text-muted); margin: 0;">You can only review products that you have successfully ordered.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p style="color: var(--text-muted);">Please <a href="/login" style="color: var(--primary); font-weight: 600;">login</a> to leave a review.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
