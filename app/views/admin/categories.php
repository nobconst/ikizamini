<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;">📁 Manage Categories</h1>

    <div style="max-width: 600px; margin-bottom: 40px;">
        <div class="card">
            <div class="card-header">➕ Add New Category</div>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" placeholder="e.g., Road Signs" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief description"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
    </div>

    <h2>📋 Existing Categories</h2>
    <table class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= htmlspecialchars($cat['description'] ?? 'N/A') ?></td>
                        <td><?= date('M d, Y', strtotime($cat['created_at'])) ?></td>
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
