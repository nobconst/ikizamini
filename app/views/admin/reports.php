<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">📊 Reports & Analytics</h1>

    <div style="margin-bottom: 40px;">
        <h2>❌ Most Failed Questions</h2>
        <table class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Times Answered</th>
                        <th>Times Correct</th>
                        <th>Success Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($failed_questions)): ?>
                        <?php foreach ($failed_questions as $q): ?>
                            <tr>
                                <td><?= htmlspecialchars(substr($q['question_text'], 0, 50)) ?>...</td>
                                <td><?= $q['times_answered'] ?></td>
                                <td><?= $q['times_correct'] ?></td>
                                <td>
                                    <span style="color: <?= $q['success_rate'] < 50 ? 'var(--danger)' : ($q['success_rate'] < 80 ? '#ffc107' : 'var(--success)') ?>;">
                                        <?= number_format($q['success_rate'], 1) ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center;">No data yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <a href="<?= SITE_URL ?>/admin/dashboard" class="btn btn-secondary">Back</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
