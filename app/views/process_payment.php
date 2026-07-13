<?php ob_start(); ?>

<div class="container" style="max-width:440px; margin:60px auto;">
    <div class="card">
        <div class="card-header">💳 Ikizamini Payment Test</div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">✓ <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= SITE_URL ?>/pawapay/process-payment">
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="25078xxxxxx" required>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" name="amount" placeholder="Amount" required>
            </div>
            <div class="form-group">
                <label>Currency</label>
                <select name="currency">
                    <option value="RWF">RWF</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Pay Now</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
