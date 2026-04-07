<?php
ob_start();
?>

<section class="hero">
    <div class="container">
        <h1><?= Translate::t('home_title') ?></h1>
        <p><?= Translate::t('home_subtitle') ?></p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="<?= SITE_URL ?>/auth/register" class="btn btn-lg btn-primary"><?= Translate::t('home_cta_free') ?></a>
        <?php else: ?>
            <a href="<?= SITE_URL ?>/test" class="btn btn-lg btn-success"><?= Translate::t('home_cta_test') ?></a>
        <?php endif; ?>
    </div>
</section>

<div class="container">
    <!-- Stats -->
    <div class="dashboard-grid">
        <div class="stats-card">
            <div class="stats-label"><?= Translate::t('home_total_users') ?></div>
            <div class="stats-value"><?= number_format($total_users) ?></div>
        </div>
        <div class="stats-card success">
            <div class="stats-label"><?= Translate::t('home_tests_completed') ?></div>
            <div class="stats-value"><?= number_format($total_tests) ?></div>
        </div>
        <div class="stats-card warning">
            <div class="stats-label"><?= Translate::t('home_languages') ?></div>
            <div class="stats-value">3+</div>
        </div>
        <div class="stats-card danger">
            <div class="stats-label"><?= Translate::t('home_categories') ?></div>
            <div class="stats-value">5</div>
        </div>
    </div>

    <!-- Features -->
    <section class="row">
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('home_feature_content') ?></div>
                <p><?= Translate::t('home_feature_content_desc') ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('home_feature_tests') ?></div>
                <p><?= Translate::t('home_feature_tests_desc') ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('home_feature_results') ?></div>
                <p><?= Translate::t('home_feature_results_desc') ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('home_feature_language') ?></div>
                <p><?= Translate::t('home_feature_language_desc') ?></p>
            </div>
        </div>
    </section>

    <!-- Pricing Preview -->
    <h2 style="text-align: center; margin: 50px 0 30px; font-size: 32px; "><?= Translate::t('home_pricing') ?></h2>
    <div class="pricing-grid">
        <?php foreach ($plans as $plan): ?>
            <div class="price-card">
                <?php if (!empty($plan['test_count'])): ?>
                    <h3><?= Translate::t('home_price_tests') ?> <?= htmlspecialchars($plan['test_count']) ?></h3>
                <?php elseif (!empty($plan['duration_days'])): ?>
                    <?php if ($plan['duration_days'] == 1): ?>
                        <h3><?= Translate::t('home_plan_daily') ?></h3>
                    <?php elseif ($plan['duration_days'] == 7): ?>
                        <h3><?= Translate::t('home_plan_weekly') ?></h3>
                    <?php elseif ($plan['duration_days'] == 30): ?>
                        <h3><?= Translate::t('home_plan_monthly') ?></h3>
                    <?php else: ?>
                        <h3><?= htmlspecialchars($plan['name']) ?></h3>
                    <?php endif; ?>
                <?php else: ?>
                    <h3><?= htmlspecialchars($plan['name']) ?></h3>
                <?php endif; ?>
                <div class="price"><?= number_format($plan['price']) ?> RWF</div>
                <?php if (!empty($plan['test_count'])): ?>
                    <div class="price-desc"><?= $plan['test_count'] ?> <?= Translate::t('home_price_tests') ?></div>
                <?php else: ?>
                    <div class="price-desc"><?= $plan['duration_days'] ?> <?= Translate::t('home_price_access') ?></div>
                <?php endif; ?>
                <ul class="price-features">
                    <li><?= Translate::t('home_plan_instant') ?></li>
                    <li><?= Translate::t('home_plan_categories') ?></li>
                    <li><?= Translate::t('home_plan_results') ?></li>
                </ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= SITE_URL ?>/payment/checkout/<?= $plan['id'] ?>" class="btn btn-primary btn-block"><?= Translate::t('home_choose_plan') ?></a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/auth/register" class="btn btn-primary btn-block"><?= Translate::t('home_register_to_buy') ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- CTA -->
    <div class="row" style="margin-top: 50px;">
        <div class="col col-12" style="text-align: center;">
            <h3><?= Translate::t('home_cta_ready') ?></h3>
            <p style="margin-bottom: 20px;"><?= Translate::t('home_cta_subtitle') ?></p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= SITE_URL ?>/auth/register" class="btn btn-lg btn-primary"><?= Translate::t('home_cta_start') ?></a>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/test" class="btn btn-lg btn-success"><?= Translate::t('home_cta_dashboard') ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
