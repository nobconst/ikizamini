<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 600px; margin: 50px auto;">
        <div class="card">
            <div class="card-header"><?= Translate::t('auth_create_account') ?></div>
            
            <form method="POST" action="<?= SITE_URL ?>/auth/registerProcess">
                <div class="form-group">
                    <label for="name"><?= Translate::t('auth_full_name') ?></label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="phone"><?= Translate::t('auth_phone') ?></label>
                    <input type="text" id="phone" name="phone" placeholder="+250780123456" required>
                </div>

                <div class="form-group">
                    <label for="password"><?= Translate::t('auth_password') ?></label>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><?= Translate::t('auth_confirm_password') ?></label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><?= Translate::t('auth_button_register') ?></button>
            </form>

            <div style="margin-top: 20px; text-align: center;">
                <p><?= Translate::t('auth_already_have_account') ?> <a href="<?= SITE_URL ?>/auth/login"><?= Translate::t('auth_login_here') ?></a></p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
