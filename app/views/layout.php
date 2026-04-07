<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ProviSor Exam System' ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/assets/css/style.css">
</head>
<body>
    <!-- Header/Navigation -->
    <header>
        <div class="container">
            <nav>
                <div class="logo">🚗 ProviSor Exam</div>
                
                <button class="menu-toggle">☰</button>
                
                <ul class="nav-menu">
                    <li><a href="<?= SITE_URL ?>/"><?= Translate::t('home') ?></a></li>
                    <li><a href="<?= SITE_URL ?>/pricing"><?= Translate::t('pricing') ?></a></li>
                    <li><a href="<?= SITE_URL ?>/about"><?= Translate::t('about') ?></a></li>
                    
                    <div class="nav-user">
                        <!-- Language Selector -->
                        <form method="POST" action="<?= SITE_URL ?>/system/set-language" style="display: inline-block; margin-right: 15px;">
                            <select name="language" onchange="this.form.submit();" style="padding: 8px 12px; border-radius: 4px; border: 1px solid #ddd; background: white; cursor: pointer;">
                                <option value="en" <?= ($current_lang ?? 'rw') === 'en' ? 'selected' : '' ?>>English</option>
                                <option value="fr" <?= ($current_lang ?? 'rw') === 'fr' ? 'selected' : '' ?>>Français</option>
                                <option value="rw" <?= ($current_lang ?? 'rw') === 'rw' ? 'selected' : '' ?>>Kinyarwanda</option>
                            </select>
                        </form>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <span>👤 <?= $_SESSION['user_name'] ?? 'User' ?></span>
                            <a href="<?= SITE_URL ?>/dashboard"><?= Translate::t('dashboard') ?></a>
                            <a href="<?= SITE_URL ?>/auth/logout" class="btn btn-sm btn-danger"><?= Translate::t('logout') ?></a>
                        <?php else: ?>
                            <a href="<?= SITE_URL ?>/auth/login" class="btn btn-sm btn-primary"><?= Translate::t('login') ?></a>
                            <a href="<?= SITE_URL ?>/auth/register" class="btn btn-sm btn-secondary"><?= Translate::t('register') ?></a>
                        <?php endif; ?>
                    </div>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="container" style="margin-top: 20px;">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✓ <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                ✗ <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning">
                ⚠ <?= $_SESSION['warning'] ?>
            </div>
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main>
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <div class="footer-section">
                <h3><?= Translate::t('footer_about') ?></h3>
                <p><?= Translate::t('footer_about_text') ?></p>
            </div>
            <div class="footer-section">
                <h3><?= Translate::t('footer_links') ?></h3>
                <ul>
                    <li><a href="<?= SITE_URL ?>/"><?= Translate::t('home') ?></a></li>
                    <li><a href="<?= SITE_URL ?>/pricing"><?= Translate::t('pricing') ?></a></li>
                    <li><a href="<?= SITE_URL ?>/about"><?= Translate::t('about') ?></a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><?= Translate::t('footer_contact') ?></h3>
                <ul>
                    <li>Email: info@provisor.com</li>
                    <li>Phone: +250 780 803 306</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p><?= Translate::t('copyright') ?></p>
        </div>
    </footer>

    <script>
        const SITE_URL = '<?= SITE_URL ?>';
        const TEST_DURATION = <?= TEST_DURATION ?? 1200 ?>;
        const TEST_ID = '<?= $_SESSION['test_id'] ?? '' ?>';
    </script>
    <script src="<?= SITE_URL ?>/public/assets/js/main.js"></script>
</body>
</html>
