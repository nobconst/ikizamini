<?php
ob_start();
?>

<div class="container">
    <h1 style="margin: 30px 0;"><?= Translate::t('dashboard_title') ?></h1>

    <!-- Stats -->
    <div class="dashboard-grid">
        <div class="stats-card">
            <div class="stats-label"><?= Translate::t('dashboard_remaining_tests') ?></div>
            <div class="stats-value"><?= $access['remaining_tests'] ?? 0 ?></div>
        </div>
        <div class="stats-card success">
            <div class="stats-label"><?= Translate::t('dashboard_tests_completed') ?></div>
            <div class="stats-value" id="total-tests">0</div>
        </div>
        <div class="stats-card warning">
            <div class="stats-label"><?= Translate::t('dashboard_avg_score') ?></div>
            <div class="stats-value" id="avg-score">0%</div>
        </div>
        <div class="stats-card danger">
            <div class="stats-label"><?= Translate::t('dashboard_pass_rate') ?></div>
            <div class="stats-value" id="pass-rate">0%</div>
        </div>
    </div>

    <!-- Access Status -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header"><?= Translate::t('dashboard_your_access') ?></div>
                <?php if ($access['unlimited']): ?>
                    <p><?= Translate::t('dashboard_unlimited_active') ?></p>
                    <?php if ($access['expires_at']): ?>
                        <p><?= Translate::t('dashboard_expires') ?> <?= date('M d, Y', strtotime($access['expires_at'])) ?></p>
                    <?php endif; ?>
                <?php elseif ($access['remaining_tests'] > 0): ?>
                    <p><?= str_replace('{count}', $access['remaining_tests'], Translate::t('dashboard_tests_remaining')) ?></p>
                <?php else: ?>
                    <p><?= Translate::t('dashboard_no_access') ?> <a href="<?= SITE_URL ?>/payment">Purchase a plan</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header"><?= Translate::t('dashboard_take_test') ?></div>
                <p><?= Translate::t('dashboard_test_desc') ?></p>
                <a href="<?= SITE_URL ?>/test" class="btn btn-primary btn-lg"><?= Translate::t('dashboard_start_test') ?></a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header"><?= Translate::t('dashboard_buy_credits') ?></div>
                <p><?= Translate::t('dashboard_buy_desc') ?></p>
                <a href="<?= SITE_URL ?>/payment" class="btn btn-success btn-lg"><?= Translate::t('dashboard_view_plans') ?></a>
            </div>
        </div>
        <div class="col">
            <div class="card" style="text-align: center;">
                <div class="card-header"><?= Translate::t('dashboard_my_history') ?></div>
                <p><?= Translate::t('dashboard_history_desc') ?></p>
                <a href="<?= SITE_URL ?>/dashboard/history" class="btn btn-info btn-lg"><?= Translate::t('dashboard_view_history') ?></a>
            </div>
        </div>
    </div>

    <!-- Recent Tests -->
    <?php if (!empty($recent_tests)): ?>
        <div style="margin-top: 40px;">
            <h2><?= Translate::t('dashboard_recent_tests') ?></h2>
            <table>
                <thead>
                    <tr>
                        <th><?= Translate::t('dashboard_date') ?></th>
                        <th><?= Translate::t('dashboard_score') ?></th>
                        <th><?= Translate::t('dashboard_status') ?></th>
                        <th><?= Translate::t('dashboard_action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_tests as $test): ?>
                        <tr>
                            <td><?= date('M d, Y H:i', strtotime($test['created_at'])) ?></td>
                            <td><strong><?= $test['score'] ?>/20</strong></td>
                            <td>
                                <?php if ($test['score'] >= 16): ?>
                                    <span style="color: var(--success);"><?= Translate::t('dashboard_passed') ?></span>
                                <?php else: ?>
                                    <span style="color: var(--danger);"><?= Translate::t('dashboard_not_passed') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= SITE_URL ?>/ikizamini/test/result/<?= $test['id'] ?>"><?= Translate::t('dashboard_view_details') ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Footer Links -->
    <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
        <a href="<?= SITE_URL ?>/dashboard/profile">👤 Edit Profile</a> | 
        <a href="<?= SITE_URL ?>/dashboard/history">📜 Full History</a> | 
        <a href="<?= SITE_URL ?>/auth/logout">🚪 Logout</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
