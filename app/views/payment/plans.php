<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">💳 <?= Translate::t('pricing_title') ?></h1>
    <p><?= Translate::t('pricing_subtitle') ?></p>

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
                    <p style="font-size: 14px; color: #666;"><?= Translate::t('pricing_one_time_purchase') ?></p>
                <?php else: ?>
                    <div class="price-desc"><?= $plan['duration_days'] ?> <?= Translate::t('home_price_access') ?></div>
                    <p style="font-size: 14px; color: #666;"><?= Translate::t('pricing_unlimited_tests') ?></p>
                <?php endif; ?>

                <ul class="price-features">
                    <li><?= Translate::t('home_plan_instant') ?></li>
                    <li><?= Translate::t('home_plan_categories') ?></li>
                    <li><?= Translate::t('home_plan_results') ?></li>
                    <li><?= Translate::t('home_plan_progress') ?></li>
                </ul>
                
                <a href="<?= SITE_URL ?>/payment/checkout/<?= $plan['id'] ?>" class="btn btn-primary btn-block"><?= Translate::t('pricing_choose_plan_button') ?></a>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="background: #f0f7ff; border-left: 4px solid #007bff; padding: 20px; border-radius: 8px; margin-top: 40px;">
        <h3>💬 <?= Translate::t('pricing_faq') ?></h3>
        <ol>
            <li><?= Translate::t('pricing_faq_q1') ?></li>
            <li><?= Translate::t('pricing_faq_q2') ?></li>
            <li><?= Translate::t('pricing_faq_q3') ?></li>
            <li><?= Translate::t('pricing_faq_q4') ?></li>
        </ol>
        <p><small><strong><?= Translate::t('pricing_faq') ?>:</strong> MTN MoMo, Airtel Money, Equity Bank</small></p>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary"><?= Translate::t('pricing_back_dashboard') ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
