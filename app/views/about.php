<?php
ob_start();
?>

<section class="hero">
    <div class="container">
        <h1><?= Translate::t('about_title') ?></h1>
        <p><?= Translate::t('about_description') ?></p>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header"><?= Translate::t('about_mission_title') ?></div>
                <p><?= Translate::t('about_mission_text') ?></p>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 40px;">
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('about_content_title') ?></div>
                <p><?= Translate::t('about_content_text') ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('about_multilang_title') ?></div>
                <p><?= Translate::t('about_multilang_text') ?></p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header"><?= Translate::t('about_analytics_title') ?></div>
                <p><?= Translate::t('about_analytics_text') ?></p>
            </div>
        </div>
    </div>

    <div style="background: #f0f7ff; border-left: 4px solid #007bff; padding: 30px; border-radius: 8px; margin: 50px 0;">
        <h2><?= Translate::t('about_how_it_works_title') ?></h2>
        <ol>
            <li><?= Translate::t('about_how_it_works_1') ?></li>
            <li><?= Translate::t('about_how_it_works_2') ?></li>
            <li><?= Translate::t('about_how_it_works_3') ?></li>
            <li><?= Translate::t('about_how_it_works_4') ?></li>
            <li><?= Translate::t('about_how_it_works_5') ?></li>
        </ol>
    </div>

    <div style="text-align: center; margin: 50px 0;">
        <a href="<?= SITE_URL ?>/" class="btn btn-lg btn-primary"><?= Translate::t('about_back_home') ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
