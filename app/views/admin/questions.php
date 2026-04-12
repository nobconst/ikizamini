<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">❓ Manage Questions 
        <a href="<?= SITE_URL ?>/admin/question/add" class="btn btn-sm btn-success">+ Add Question</a>
        <a href="<?= SITE_URL ?>/admin/driving-test-import" class="btn btn-sm btn-info"> Import CSV</a>
    </h1>

    <table class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question (<?= strtoupper($lang) ?>)</th>
                    <th>Category</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $idx => $q): ?>
                    <tr>
                        <td><?= ($page - 1) * 20 + $idx + 1 ?></td>
                        <td><?= substr(htmlspecialchars($q['question_text'] ?? ''), 0, 50) ?>...</td>
                        <td><?= htmlspecialchars($q['category_name'] ?? 'N/A') ?></td>
                        <td><?= date('M d, Y', strtotime($q['created_at'])) ?></td>
                        <td>
                            <a href="<?= SITE_URL ?>/admin/viewQuestion/<?= $q['id'] ?>" style="color: var(--info);">View</a> | 
                            <a href="<?= SITE_URL ?>/admin/editQuestion/<?= $q['id'] ?>" style="color: var(--warning);">Edit</a> | 
                            <a href="<?= SITE_URL ?>/admin/deleteQuestion/<?= $q['id'] ?>" onclick="return confirm('Delete question?')" style="color: var(--danger);">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1">« First</a>
            <a href="?page=<?= $page - 1 ?>">‹ Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>">Next ›</a>
            <a href="?page=<?= $total_pages ?>">Last »</a>
        <?php endif; ?>
    </div>

    <div style="margin-top: 30px;">
        <a href="<?= SITE_URL ?>/admin/dashboard" class="btn btn-secondary">Back</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
