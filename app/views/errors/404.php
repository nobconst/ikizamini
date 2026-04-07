<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">🚗 ProviSor Exam</div>
                <a href="<?= SITE_URL ?>/" class="btn btn-sm btn-primary">Home</a>
            </nav>
        </div>
    </header>

    <div class="container" style="text-align: center; padding: 80px 0;">
        <h1 style="font-size: 72px; font-weight: 700; color: #007bff; margin-bottom: 20px;">404</h1>
        <h2 style="margin-bottom: 20px;"><?= Translate::t('404_header') ?></h2>
        <p style="color: #666; font-size: 18px; margin-bottom: 30px;">
            <?= Translate::t('404_text') ?>
        </p>
        <a href="<?= SITE_URL ?>/" class="btn btn-lg btn-primary"><?= Translate::t('404_button') ?></a>
    </div>

    <footer>
        <div class="footer-bottom" style="text-align: center;">
            <p>&copy; 2024 ProviSor Exam System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
