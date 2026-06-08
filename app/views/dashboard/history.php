<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">📜 <?= Translate::t('dashboard_recent_tests') ?></h1>

    <?php if (!empty($tests)): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= Translate::t('dashboard_date') ?></th>
                    <th><?= Translate::t('dashboard_score') ?></th>
                    <th><?= Translate::t('dashboard_status') ?></th>
                    <th><?= Translate::t('dashboard_action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tests as $idx => $test): ?>
                    <tr>
                        <td><?= ($current_page - 1) * 10 + $idx + 1 ?></td>
                        <td><?= date('M d, Y H:i', strtotime($test['created_at'])) ?></td>
                        <td><strong><?= $test['score'] ?>/20</strong></td>
                        <td>
                            <?php if ($test['score'] >= 16): ?>
                                <span style="color: var(--success);"><?= Translate::t('dashboard_passed') ?> (<?= round(($test['score'] / 20) * 100) ?>%)</span>
                            <?php else: ?>
                                <span style="color: var(--danger);"><?= Translate::t('dashboard_not_passed') ?> (<?= round(($test['score'] / 20) * 100) ?>%)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= SITE_URL ?>/test/result/<?= $test['id'] ?>"><?= Translate::t('dashboard_view_details') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=1">«</a>
                <a href="?page=<?= $current_page - 1 ?>"><?= Translate::t('test_previous') ?></a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>"><?= Translate::t('test_next') ?></a>
                <a href="?page=<?= $total_pages ?>">»</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Nta bizamini byaboneka. <a href="<?= SITE_URL ?>/test">Fata ikizamini cyawe cya mbere</a>
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
