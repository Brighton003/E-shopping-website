<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paulz' E-Shopping</title>
    <meta name="description" content="Paulz' E-Shopping — Uganda's premier multi-vendor marketplace.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script>
        // Init theme immediately to prevent flashing
        const savedTheme = localStorage.getItem('theme') || 'dark';
        if (savedTheme === 'light') document.documentElement.setAttribute('data-theme', 'light');
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="/" class="brand">⚡ Paulz'</a>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search products, brands and categories…">
                <button class="btn btn-primary" onclick="doSearch()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <ul class="nav-links">
                <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#" onclick="toggleTheme(event)"><i class="fas fa-moon" id="themeIcon"></i> Theme</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="/admin/dashboard"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                    <?php elseif ($_SESSION['user_role'] === 'vendor'): ?>
                        <li><a href="/vendor/dashboard"><i class="fas fa-store"></i> Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="/orders"><i class="fas fa-box"></i> Orders</a></li>
                    <?php endif; ?>
                    <li><a href="#"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_name']) ?></a></li>
                    <li><a href="/cart"><i class="fas fa-shopping-cart"></i> <span id="cartCount" class="badge">0</span></a></li>
                    <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="/login"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="/register" class="btn btn-primary" style="padding:0.45rem 1rem; font-size:0.85rem;">Register</a></li>
                    <li><a href="/cart"><i class="fas fa-shopping-cart"></i> <span id="cartCount" class="badge">0</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="container content">
        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p style="margin-bottom:0.5rem;">
                <strong style="background:linear-gradient(135deg,#f68b1e,#ffb74d);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Paulz' E-Shopping</strong>
            </p>
            <p>&copy; <?= date('Y') ?> Paulz' E-Shopping. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="/assets/js/app.js"></script>
    <script>
    function doSearch() {
        const q = document.getElementById('searchInput').value.trim();
        if (q) window.location.href = '/search?q=' + encodeURIComponent(q);
    }
    document.getElementById('searchInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') doSearch();
    });

    function toggleTheme(e) {
        if(e) e.preventDefault();
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    }

    function updateThemeIcon(theme) {
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.className = theme === 'light' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }

    // Set initial icon
    updateThemeIcon(document.documentElement.getAttribute('data-theme') || 'dark');
    </script>
</body>
</html>
