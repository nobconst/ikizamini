<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">📜 Test History</h1>

    <?php if (!empty($tests)): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Score</th>
                    <th>Result</th>
                    <th>Action</th>
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
                                <span style="color: var(--success);">✓ Passed (<?= round(($test['score'] / 20) * 100) ?>%)</span>
                            <?php else: ?>
                                <span style="color: var(--danger);">✗ Not Passed (<?= round(($test['score'] / 20) * 100) ?>%)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= SITE_URL ?>/ikizamini/test/result/<?= $test['id'] ?>">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=1">« First</a>
                <a href="?page=<?= $current_page - 1 ?>">‹ Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>">Next ›</a>
                <a href="?page=<?= $total_pages ?>">Last »</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No tests yet. <a href="<?= SITE_URL ?>/test">Take your first test</a>
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
