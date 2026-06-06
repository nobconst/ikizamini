<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">💳 <?= Translate::t('payment_history_title') ?></h1>

    <?php if (!empty($history)): ?>
        <table class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= Translate::t('payment_history_plan') ?></th>
                        <th><?= Translate::t('payment_history_amount') ?></th>
                        <th><?= Translate::t('payment_history_method') ?></th>
                        <th><?= Translate::t('payment_history_status') ?></th>
                        <th><?= Translate::t('payment_history_date') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $payment): ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['plan_name']) ?></td>
                            <td><?= number_format($payment['amount']) ?> RWF</td>
                            <td><?= Translate::t('payment_method_' . strtolower($payment['payment_method'] ?? 'unknown')) ?></td>
                            <td>
                                <span style="color: <?= $payment['status'] === 'success' ? 'var(--success)' : ($payment['status'] === 'failed' ? 'var(--danger)' : '#ffc107') ?>;">
                                    <?= Translate::t('payment_status_' . strtolower($payment['status'])) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y H:i', strtotime($payment['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </table>
    <?php else: ?>
        <div class="alert alert-info">
            <?= Translate::t('payment_history_no_history') ?> <a href="<?= SITE_URL ?>/payment"><?= Translate::t('dashboard_purchase_plan') ?></a>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px;">
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary"><?= Translate::t('back_to_dashboard') ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
