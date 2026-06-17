<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card">
        <div style="text-align:center;margin-bottom:1.8rem;">
            <div style="width:60px;height:60px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;box-shadow:0 0 20px rgba(246,139,30,0.4);">
                <i class="fas fa-sign-in-alt" style="font-size:1.5rem;color:#fff;"></i>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to your account to continue</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:0.5rem;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.88rem;color:var(--text-muted);">
            Don't have an account? <a href="/register" style="color:var(--primary);font-weight:600;">Register here</a>
        </p>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
