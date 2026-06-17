<?php
// Shared form partial — used by both vendor create and vendor edit
// Variables: $product (optional), $categories, $formAction, $formTitle
$isEdit = isset($product);
ob_start();
?>

<div class="auth-container" style="align-items:flex-start;padding:2rem 0;">
    <div class="auth-card" style="max-width:640px;width:100%;">
        <div style="margin-bottom:1.8rem;">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;box-shadow:0 0 15px rgba(246,139,30,0.3);">
                    <i class="fas <?= $isEdit ? 'fa-edit' : 'fa-plus' ?>" style="color:#fff;font-size:1.2rem;"></i>
                </div>
                <div>
                    <h2 style="font-size:1.3rem;"><?= $isEdit ? 'Edit Product' : 'Add New Product' ?></h2>
                    <p style="font-size:0.85rem;">Fill in the details below</p>
                </div>
            </div>
        </div>

        <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Product Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Samsung Galaxy A54"
                    value="<?= $isEdit ? htmlspecialchars($product['name']) : '' ?>">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00"
                        value="<?= $isEdit ? htmlspecialchars($product['price']) : '' ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-warehouse"></i> Stock Qty</label>
                    <input type="number" name="stock" class="form-control" required placeholder="0"
                        value="<?= $isEdit ? htmlspecialchars($product['stock']) : '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-th-large"></i> Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select a category</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($isEdit && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe your product…"><?= $isEdit ? htmlspecialchars($product['description']) : '' ?></textarea>
            </div>

            <?php if($isEdit && $product['image']): ?>
            <div class="form-group">
                <label><i class="fas fa-image"></i> Current Image</label>
                <?php $src = str_starts_with($product['image'],'http') ? $product['image'] : '/assets/images/'.$product['image']; ?>
                <img src="<?= $src ?>" alt="Current" style="width:120px;height:120px;object-fit:cover;border-radius:var(--radius-sm);display:block;margin-bottom:0.8rem;border:1px solid var(--border);">
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label><i class="fas fa-upload"></i> <?= $isEdit ? 'Replace Image (optional)' : 'Product Image' ?></label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div style="display:flex;gap:1rem;margin-top:0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">
                    <i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus-circle' ?>"></i>
                    <?= $isEdit ? 'Save Changes' : 'Add Product' ?>
                </button>
                <a href="/vendor/dashboard" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
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
