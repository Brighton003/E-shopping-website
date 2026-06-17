<?php ob_start(); ?>

<div class="auth-container" style="align-items:flex-start;padding:2rem 0;">
    <div class="auth-card" style="max-width:500px;width:100%;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.8rem;">
            <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;box-shadow:0 0 15px rgba(246,139,30,0.3);">
                <i class="fas fa-user-edit" style="color:#fff;font-size:1.2rem;"></i>
            </div>
            <div>
                <h2 style="font-size:1.3rem;">Edit User</h2>
                <p style="font-size:0.85rem;">Update account details for <?= htmlspecialchars($user['name']) ?></p>
            </div>
        </div>

        <form action="/admin/users/edit/<?= $user['id'] ?>" method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-id-badge"></i> Role</label>
                <select name="role" class="form-control">
                    <option value="customer" <?= $user['role']==='customer' ? 'selected' : '' ?>>🛒 Customer</option>
                    <option value="vendor"   <?= $user['role']==='vendor'   ? 'selected' : '' ?>>🏪 Vendor</option>
                    <option value="admin"    <?= $user['role']==='admin'    ? 'selected' : '' ?>>🛡️ Admin</option>
                </select>
            </div>
            <div style="display:flex;gap:1rem;margin-top:0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="/admin/users" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
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
