<?php
$formAction = '/admin/products/edit/' . $product['id'];
ob_start();
?>

<div class="auth-container" style="align-items:flex-start;padding:2rem 0;">
    <div class="auth-card" style="max-width:640px;width:100%;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.8rem;">
            <div style="width:48px;height:48px;background:linear-gradient(135deg,#9c27b0,#6a1b9a);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;box-shadow:0 0 15px rgba(156,39,176,0.3);">
                <i class="fas fa-boxes" style="color:#fff;font-size:1.1rem;"></i>
            </div>
            <div>
                <h2 style="font-size:1.3rem;">Edit Product <span style="font-size:0.9rem;background:rgba(156,39,176,0.2);padding:0.2rem 0.6rem;border-radius:50px;color:#ce93d8;">Admin</span></h2>
                <p style="font-size:0.85rem;">Platform-wide product editing</p>
            </div>
        </div>

        <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Product Name</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required value="<?= htmlspecialchars($product['price']) ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-warehouse"></i> Stock Qty</label>
                    <input type="number" name="stock" class="form-control" required value="<?= htmlspecialchars($product['stock']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-th-large"></i> Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select category</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $product['category_id']==$cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <?php if($product['image']): 
                $src = str_starts_with($product['image'],'http') ? $product['image'] : '/assets/images/'.$product['image'];
            ?>
            <div class="form-group">
                <label><i class="fas fa-image"></i> Current Image</label>
                <img src="<?= $src ?>" alt="Current" style="width:120px;height:120px;object-fit:cover;border-radius:var(--radius-sm);display:block;margin-bottom:0.8rem;border:1px solid var(--border);">
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label><i class="fas fa-upload"></i> Replace Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div style="display:flex;gap:1rem;margin-top:0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="/admin/products" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP . '/views/layouts/main.php';
?>
