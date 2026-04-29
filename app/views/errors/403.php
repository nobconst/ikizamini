<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'rw') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo"><?= SITE_NAME ?></div>
                <a href="<?= SITE_URL ?>/" class="btn btn-sm btn-primary"><?= Translate::t('home') ?></a>
            </nav>
        </div>
    </header>

    <div class="container" style="text-align: center; padding: 80px 0;">
        <h1 style="font-size: 72px; font-weight: 700; color: #dc3545; margin-bottom: 20px;">403</h1>
        <h2 style="margin-bottom: 20px;">Access Denied</h2>
        <p style="color: #666; font-size: 18px; margin-bottom: 30px;">
            You don't have permission to access this page.
        </p>
        <a href="<?= SITE_URL ?>/" class="btn btn-lg btn-primary"><?= Translate::t('404_button') ?></a>
    </div>

    <footer>
        <div class="footer-bottom" style="text-align: center;">
            <p><?= Translate::t('copyright') ?></p>
        </div>
    </footer>
</body>
</html>
