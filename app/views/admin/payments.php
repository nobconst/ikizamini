<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">💳 Payment Transactions</h1>

    <table class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= $payment['id'] ?></td>
                        <td><?= htmlspecialchars($payment['user_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($payment['plan_name']) ?></td>
                        <td><?= number_format($payment['amount']) ?> RWF</td>
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

    <div style="margin-top: 30px;">
        <a href="<?= SITE_URL ?>/admin/dashboard" class="btn btn-secondary">Back</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
