<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">💳 Payment History</h1>

    <?php if (!empty($history)): ?>
        <table class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $payment): ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['plan_name']) ?></td>
                            <td><?= number_format($payment['amount']) ?> RWF</td>
                            <td><?= ucfirst($payment['payment_method'] ?? 'N/A') ?></td>
                            <td>
                                <span style="color: <?= $payment['status'] === 'success' ? 'var(--success)' : ($payment['status'] === 'failed' ? 'var(--danger)' : '#ffc107') ?>;">
                                    <?= ucfirst($payment['status']) ?>
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
            No payment history. <a href="<?= SITE_URL ?>/payment">Purchase a plan</a>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px;">
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
