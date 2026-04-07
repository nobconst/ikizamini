<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">👥 Manage Users</h1>

    <table class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td>
                            <span style="color: <?= $user['status'] === 'active' ? 'var(--success)' : 'var(--danger)' ?>;">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['status'] === 'active'): ?>
                                <a href="<?= SITE_URL ?>/admin/blockUser/<?= $user['id'] ?>" onclick="return confirm('Block user?')">Block</a>
                            <?php else: ?>
                                <a href="<?= SITE_URL ?>/admin/unblockUser/<?= $user['id'] ?>">Unblock</a>
                            <?php endif; ?>
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
