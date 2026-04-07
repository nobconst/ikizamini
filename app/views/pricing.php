<?php
ob_start();
?>

<section class="hero">
    <div class="container">
        <h1><?= Translate::t('pricing_title') ?></h1>
        <p><?= Translate::t('pricing_subtitle') ?></p>
    </div>
</section>

<div class="container">
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
                    <li><?= Translate::t('home_plan_progress') ?></li>
                </ul>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= SITE_URL ?>/payment/checkout/<?= $plan['id'] ?>" class="btn btn-primary btn-block"><?= Translate::t('pricing_choose_plan_button') ?></a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/auth/register" class="btn btn-primary btn-block"><?= Translate::t('pricing_register_to_buy') ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="background: #f0f7ff; border-left: 4px solid #007bff; padding: 30px; border-radius: 8px; margin-top: 50px;">
        <h3>❓ <?= Translate::t('pricing_faq') ?></h3>
        <div style="margin-top: 20px;">
            <h4><?= Translate::t('pricing_faq_q1') ?></h4>
            <p><?= Translate::t('pricing_faq_a1') ?></p>

            <h4><?= Translate::t('pricing_faq_q2') ?></h4>
            <p><?= Translate::t('pricing_faq_a2') ?></p>

            <h4><?= Translate::t('pricing_faq_q3') ?></h4>
            <p><?= Translate::t('pricing_faq_a3') ?></p>

            <h4><?= Translate::t('pricing_faq_q4') ?></h4>
            <p><?= Translate::t('pricing_faq_a4') ?></p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 50px;">
        <a href="<?= SITE_URL ?>/" class="btn btn-lg btn-secondary"><?= Translate::t('pricing_back_dashboard') ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
