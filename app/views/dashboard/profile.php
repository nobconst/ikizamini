<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 800px; margin: 50px auto;">
        <div class="card">
            <div class="card-header">👤 My Profile</div>
            
            <form method="POST" action="<?= SITE_URL ?>/dashboard/profile">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="joined">Joined Date</label>
                    <input type="text" id="joined" value="<?= date('M d, Y', strtotime($user['created_at'])) ?>" disabled>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary">Back to Dashboard</a>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
