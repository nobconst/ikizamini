<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 600px; margin: 50px auto;">
        <div class="card">
            <div class="card-header">💳 <?= Translate::t('payment_checkout_title') ?></div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p><strong><?= htmlspecialchars($plan['name']) ?></strong></p>
                <p style="font-size: 24px; font-weight: 700; color: var(--primary);">
                    <?= number_format($plan['price']) ?> RWF
                </p>
                <?php if ($plan['test_count']): ?>
                    <p style="color: #666;"><?= $plan['test_count'] ?> <?= Translate::t('home_price_tests') ?></p>
                <?php else: ?>
                    <p style="color: #666;"><?= $plan['duration_days'] ?> <?= Translate::t('home_price_access') ?> <?= Translate::t('payment_unlimited_access') ?></p>
                <?php endif; ?>
            </div>

            <form method="POST" action="<?= SITE_URL ?>/payment/processPayment">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                <div class="form-group">
                    <label for="phone"><?= Translate::t('payment_mobile_money_number') ?></label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            placeholder="0780123456"
                            pattern="^07[0-9]{8}$"
                            maxlength="10"
                            minlength="10"
                            inputmode="numeric"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                            required
                        >
                    <small style="color: #666;"><?= Translate::t('payment_phone_note') ?></small>
                </div>

                <div class="form-group">
                    <label for="payment_method"><?= Translate::t('payment_method') ?></label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="momo"><?= Translate::t('payment_method_momo') ?></option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><?= Translate::t('payment_pay_button') ?> <?= number_format($plan['price']) ?> RWF</button>
            </form>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                <small style="color: #666;"><?= Translate::t('payment_secure_message') ?></small>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
