<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">🔒 <?= Translate::t('admin_dashboard') ?></h1>

    <!-- Stats -->
    <div class="dashboard-grid">
        <div class="stats-card">
            <div class="stats-label"><?= Translate::t('total_users') ?></div>
            <div class="stats-value"><?= number_format($total_users) ?></div>
        </div>
        <div class="stats-card success">
            <div class="stats-label"><?= Translate::t('total_questions') ?></div>
            <div class="stats-value"><?= number_format($total_questions) ?></div>
        </div>
        <div class="stats-card warning">
            <div class="stats-label"><?= Translate::t('tests_taken') ?></div>
            <div class="stats-value"><?= number_format($total_tests) ?></div>
        </div>
        <div class="stats-card danger">
            <div class="stats-label"><?= Translate::t('total_revenue') ?></div>
            <div class="stats-value"><?= number_format($stats['total_revenue']) ?> RWF</div>
        </div>
    </div>

    <!-- Management Links -->
    <h2 style="margin: 40px 0 20px;">⚙️ <?= Translate::t('management') ?></h2>
    <div class="row">
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header">👥 <?= Translate::t('users') ?></div>
                <p><?= number_format($total_users) ?> <?= Translate::t('users') ?> <?= Translate::t('registered') ?? 'registered' ?></p>
                <a href="<?= SITE_URL ?>/admin/users" class="btn btn-primary"><?= Translate::t('manage_users') ?></a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header">❓ <?= Translate::t('questions') ?></div>
                <p><?= number_format($total_questions) ?> <?= Translate::t('questions') ?> <?= Translate::t('available') ?? 'available' ?></p>
                <a href="<?= SITE_URL ?>/admin/questions" class="btn btn-primary"><?= Translate::t('manage_questions') ?></a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header">📁 <?= Translate::t('categories') ?></div>
                <p><?= number_format(count($categories ?? [])) ?> <?= Translate::t('categories') ?></p>
                <a href="<?= SITE_URL ?>/admin/categories" class="btn btn-primary"><?= Translate::t('manage_categories') ?></a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header">💳 <?= Translate::t('payments') ?></div>
                <p><?= number_format($stats['successful_payments'] ?? 0) ?> <?= Translate::t('successful') ?? 'successful' ?></p>
                <a href="<?= SITE_URL ?>/admin/payments" class="btn btn-primary"><?= Translate::t('view_payments') ?></a>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <h2 style="margin: 40px 0 20px;">📊 <?= Translate::t('reports') ?></h2>
    <div class="row">
        <div class="col col-12">
            <div class="card" style="text-align: center;">
                <div class="card-header">📈 <?= Translate::t('analytics') ?></div>
                <p><?= Translate::t('analytics_summary') ?? 'Most failed questions, user performance, trends' ?></p>
                <a href="<?= SITE_URL ?>/admin/reports" class="btn btn-primary"><?= Translate::t('view_reports') ?></a>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        <a href="<?= SITE_URL ?>/auth/logout" class="btn btn-danger">Logout</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
