<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 600px; margin: 50px auto;">
        <div class="card">
            <div class="card-header"><?= Translate::t('auth_login_title') ?></div>
            
            <form method="POST" action="<?= SITE_URL ?>/auth/loginProcess">
                <div class="form-group">
                    <label for="phone"><?= Translate::t('auth_phone') ?></label>
                    <input type="text" id="phone" name="phone" placeholder="+250780123456" required>
                </div>

                <div class="form-group">
                    <label for="password"><?= Translate::t('auth_password') ?></label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><?= Translate::t('auth_button_login') ?></button>
            </form>

            <div style="margin-top: 20px; text-align: center;">
                <p><?= Translate::t('auth_no_account') ?> <a href="<?= SITE_URL ?>/auth/register"><?= Translate::t('auth_register_here') ?></a></p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
