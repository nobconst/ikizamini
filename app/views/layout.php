<!DOCTYPE html>
<html lang="<?= htmlspecialchars($current_lang ?? 'rw') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ProviSor Exam System' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= SITE_URL ?>/public/assets/images/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= SITE_URL ?>/public/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE_URL ?>/public/assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_URL ?>/public/assets/images/apple-touch-icon.png">
    <link rel="manifest" href="<?= SITE_URL ?>/public/assets/images/site.webmanifest">

    <link rel="stylesheet" href="<?= SITE_URL ?>/public/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../../public/assets/css/style.css') ?>">
</head>
<body>
    <!-- Header/Navigation -->
    <header class="site-header">
        <div class="container">
            <nav class="site-nav" aria-label="Main navigation">
                <a href="<?= SITE_URL ?>/" class="logo" aria-label="ProviSor Exam home">
                    <span class="logo-mark" aria-hidden="true">P</span>
                    <span>ProviSor Exam</span>
                </a>

                <button class="menu-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="primaryNav">
                    <span aria-hidden="true"></span>
                </button>

                <ul class="nav-menu" id="primaryNav">
                    <li class="nav-menu-header">
                        <span class="nav-menu-title"><?= Translate::t('menu') ?></span>
                        <button class="nav-close-btn" id="navCloseBtn" type="button" aria-label="Close menu">&times;</button>
                    </li>

                    <li><a href="<?= SITE_URL ?>/"><?= Translate::t('home') ?></a></li>
                    <li><a href="<?= SITE_URL ?>/pricing"><?= Translate::t('pricing') ?></a></li>
                    <li><a href="<?= SITE_URL ?>/about"><?= Translate::t('about') ?></a></li>

                    <li class="nav-user">
                        <!-- Language Selector -->
                        <form method="POST" action="<?= SITE_URL ?>/system/set-language" class="language-form">
                            <select name="language" onchange="this.form.submit();" aria-label="Select language">
                                <option value="rw" <?= ($current_lang ?? 'rw') === 'rw' ? 'selected' : '' ?>>Kinyarwanda</option>
                                <option value="en" <?= ($current_lang ?? 'rw') === 'en' ? 'selected' : '' ?>>English</option>
                            </select>
                        </form>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <span class="nav-user-name"><?= $_SESSION['user_name'] ?? 'User' ?></span>
                            <a href="<?= SITE_URL ?>/dashboard"><?= Translate::t('dashboard') ?></a>
                            <a href="<?= SITE_URL ?>/auth/logout" class="btn btn-sm btn-danger"><?= Translate::t('logout') ?></a>
                        <?php else: ?>
                            <a href="<?= SITE_URL ?>/auth/login" class="btn btn-sm btn-primary"><?= Translate::t('login') ?></a>
                            <a href="<?= SITE_URL ?>/auth/register" class="btn btn-sm btn-secondary"><?= Translate::t('register') ?></a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

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
                × <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning">
                ! <?= $_SESSION['warning'] ?>
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
    <script src="<?= SITE_URL ?>/public/assets/js/main.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/main.js') ?>"></script>
</body>
</html>
