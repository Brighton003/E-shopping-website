<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card">
        <div style="text-align:center;margin-bottom:1.8rem;">
            <div style="width:60px;height:60px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;box-shadow:0 0 20px rgba(246,139,30,0.4);">
                <i class="fas fa-user-plus" style="font-size:1.5rem;color:#fff;"></i>
            </div>
            <h2>Create Account</h2>
            <p>Join Paulz' E-Shopping today</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/register" method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label><i class="fas fa-id-badge"></i> Account Type</label>
                <select name="role" class="form-control">
                    <option value="customer">🛒 Customer</option>
                    <option value="vendor">🏪 Vendor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.88rem;color:var(--text-muted);">
            Already have an account? <a href="/login" style="color:var(--primary);font-weight:600;">Sign in here</a>
        </p>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
