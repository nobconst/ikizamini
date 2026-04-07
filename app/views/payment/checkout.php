<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 600px; margin: 50px auto;">
        <div class="card">
            <div class="card-header">💳 Checkout</div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p><strong><?= htmlspecialchars($plan['name']) ?></strong></p>
                <p style="font-size: 24px; font-weight: 700; color: var(--primary);">
                    <?= number_format($plan['price']) ?> RWF
                </p>
                <?php if ($plan['test_count']): ?>
                    <p style="color: #666;"><?= $plan['test_count'] ?> test(s)</p>
                <?php else: ?>
                    <p style="color: #666;"><?= $plan['duration_days'] ?> day(s) unlimited access</p>
                <?php endif; ?>
            </div>

            <form method="POST" action="<?= SITE_URL ?>/payment/processPayment">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                <div class="form-group">
                    <label for="phone">Mobile Money Number</label>
                    <input type="text" id="phone" name="phone" placeholder="+250780123456" required>
                    <small style="color: #666;">Your mobile money account number</small>
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="momo">MTN MoMo</option>
                        <option value="airtel">Airtel Money</option>
                        <option value="equity">Equity Bank</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Pay <?= number_format($plan['price']) ?> RWF</button>
            </form>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
                <small style="color: #666;">Your payment is secure and encrypted</small>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
