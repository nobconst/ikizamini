<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 700px; margin: 50px auto;">
        <div class="card">
                <div class="card-header">🧪 <?= Translate::t('start_test') ?></div>
            
                <div style="text-align: center;">
                    <p style="font-size: 18px; margin-bottom: 20px;"><?php echo Translate::t('home_subtitle') ?></p>
                
                <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: center;">
                        <div>
                            <div style="font-size: 14px; color: #666; margin-bottom: 5px;"><?= Translate::t('test_questions_count') ?></div>
                            <div style="font-size: 32px; font-weight: 700; color: #007bff;"><?= TOTAL_QUESTIONS ?></div>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: #666; margin-bottom: 5px;"><?= Translate::t('test_minutes') ?></div>
                            <div style="font-size: 32px; font-weight: 700; color: #007bff;"><?= floor(TEST_DURATION/60) ?> <?= Translate::t('test_minutes') ?></div>
                        </div>
                    </div>
                </div>

                <div style="text-align: left; background: #f0f7ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px; margin-bottom: 30px;">
                    <h3 style="margin-top: 0;">📋 Amabwiriza y'ikizamini:</h3>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Ugomba gusubiza ibibazo byose <?= TOTAL_QUESTIONS ?></li>
                        <li>Ikibazo cyose gifite ibisubizo 4 bishoboka</li>
                        <li>Ukeneye amanota <?= PASS_SCORE ?>/<?= TOTAL_QUESTIONS ?> (<?= round((PASS_SCORE / TOTAL_QUESTIONS) * 100) ?>%) ngo utsinde</li>
                        <li>Ntushobora gusubira inyuma cyangwa guhagarika ikizamini</li>
                        <li>Gukoresha ubwambuzi biramenyekanwa kandi ntibyemewe</li>
                    </ul>
                </div>

                <form method="POST" action="<?= SITE_URL ?>/test/start">
                    <button type="submit" class="btn btn-lg btn-success btn-block"><?= Translate::t('dashboard_start_test') ?> 🚀</button>
                </form>

                <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary btn-block" style="margin-top: 10px;"><?= Translate::t('back_to_dashboard') ?></a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
