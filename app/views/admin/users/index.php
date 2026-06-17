<?php 
// Shared admin table page helper
$pageTitle   = $pageTitle   ?? 'Admin Panel';
$pageIcon    = $pageIcon    ?? 'fa-cog';
$backLink    = $backLink    ?? '/admin/dashboard';
ob_start(); 
?>

<div class="admin-dashboard" style="margin-top:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h2><i class="fas <?= $pageIcon ?>" style="color:var(--primary);"></i> <?= $pageTitle ?></h2>
            <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.3rem;">Admin Management Panel</p>
        </div>
        <a href="<?= $backLink ?>" class="btn" style="background:rgba(255,255,255,0.08);border:1px solid var(--border);color:var(--text);">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div style="overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border);">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allUsers as $user): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:0.82rem;">#<?= $user['id'] ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($user['name']) ?></td>
                    <td style="color:var(--text-muted);"><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php $roleColors = ['admin'=>'#9c27b0','vendor'=>'var(--primary)','customer'=>'#17a2b8']; ?>
                        <span style="padding:0.25rem 0.65rem;border-radius:50px;font-size:0.76rem;font-weight:600;background:<?= $roleColors[$user['role']] ?? '#666' ?>;color:#fff;">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td>
                        <?php $suspended = ($user['status'] ?? 'active') === 'suspended'; ?>
                        <span style="padding:0.25rem 0.65rem;border-radius:50px;font-size:0.76rem;font-weight:600;background:<?= $suspended ? '#dc3545' : '#28a745' ?>;color:#fff;">
                            <i class="fas <?= $suspended ? 'fa-ban' : 'fa-check-circle' ?>"></i>
                            <?= ucfirst($user['status'] ?? 'active') ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn btn-primary" style="padding:0.35rem 0.8rem;font-size:0.78rem;text-decoration:none;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if(!$suspended): ?>
                                <form action="/admin/users/suspend/<?= $user['id'] ?>" method="POST" onsubmit="return confirm('Suspend this user?');">
                                    <button type="submit" style="padding:0.35rem 0.8rem;font-size:0.78rem;background:#f0ad4e;color:#000;border:none;border-radius:var(--radius-sm);cursor:pointer;font-weight:600;">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="/admin/users/activate/<?= $user['id'] ?>" method="POST">
                                    <button type="submit" style="padding:0.35rem 0.8rem;font-size:0.78rem;background:#28a745;color:#fff;border:none;border-radius:var(--radius-sm);cursor:pointer;font-weight:600;">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <?php if($user['id'] != $_SESSION['user_id']): ?>
                                <form action="/admin/users/delete/<?= $user['id'] ?>" method="POST" onsubmit="return confirm('Permanently delete this user?');">
                                    <button type="submit" class="btn btn-danger" style="padding:0.35rem 0.8rem;font-size:0.78rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$pageTitle = 'Manage Users';
$pageIcon  = 'fa-users';
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
