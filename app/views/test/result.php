<?php
ob_start();
?>

<div class="container">
    <!-- Score Display -->
    <div class="result-score">
        <div class="score-circle <?= $passed ? 'passed' : 'failed' ?>">
            <?= $session['score'] ?>/20
        </div>
        <div class="score-status <?= $passed ? 'passed' : 'failed' ?>">
            <?= $passed ? Translate::t('test_you_passed') : Translate::t('test_you_not_passed') ?>
        </div>
        <p style="margin: 10px 0; color: #666;">
            <?= Translate::t('test_your_score') ?> <?= $session['score'] ?> <?= Translate::t('test_out_of_20') ?> (<?= round(($session['score'] / 20) * 100) ?>%)
        </p>
        <p style="color: #666; font-size: 14px;">
            <?php if ($passed): ?>
                <?= Translate::t('test_you_passed') ?>
            <?php else: ?>
                <?= Translate::t('test_you_not_passed') ?>. <?= Translate::t('test_your_score') ?> <?= $session['score'] ?> <?= Translate::t('test_out_of_20') ?>. <?= ($pass_score - $session['score']) ?> more needed to pass (<?= $pass_score ?>/20).
            <?php endif; ?>
        </p>
    </div>

    <!-- Answer Review -->
    <h2 style="margin: 40px 0 20px;">📋 <?= Translate::t('test_answer_review') ?></h2>
    
    <?php
    $correctCount = $incorrectCount = 0;
    $resultsByQuestion = [];
    
    foreach ($answers as $answer) {
        if (!isset($resultsByQuestion[$answer['q_id']])) {
            $resultsByQuestion[$answer['q_id']] = [
                'text' => $answer['question_text'],
                'answers' => []
            ];
        }
        
        $resultsByQuestion[$answer['q_id']]['answers'][] = $answer;
        
        if ($answer['is_correct']) {
            $correctCount++;
        } else {
            $incorrectCount++;
        }
    }
    ?>

    <div class="result-answers">
        <?php foreach ($answers as $idx => $answer): ?>
            <div class="answer-result <?= $answer['is_correct'] ? 'correct' : 'incorrect' ?>">
                <div class="answer-result-question">
                    <?= htmlspecialchars($answer['question_text']) ?>
                </div>
                <div style="margin-left: 30px; color: #666;">
                    <p><strong><?= $_SESSION['lang'] === 'fr' ? 'Votre réponse:' : ($_SESSION['lang'] === 'rw' ? 'Ibisubizo byawe:' : 'Your answer:') ?></strong> <?= htmlspecialchars($answer['user_answer']) ?? ($_SESSION['lang'] === 'fr' ? 'Aucune réponse' : ($_SESSION['lang'] === 'rw' ? 'Nta bisubizo' : 'No answer')) ?></p>
                    <?php if (!$answer['is_correct']): ?>
                        <p><strong><?= $_SESSION['lang'] === 'fr' ? 'Réponse correcte:' : ($_SESSION['lang'] === 'rw' ? 'Ibisubizo byizana:' : 'Correct answer:') ?></strong> <span style="color: var(--success);"><?= $_SESSION['lang'] === 'fr' ? 'Vous devriez sélectionner la réponse correcte' : ($_SESSION['lang'] === 'rw' ? 'Ushobora guhitamo ibisubizo byizana' : 'You should select the correct answer') ?></span></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Summary -->
    <div style="display: grid; grid-template-columns: repeatauto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 40px; margin-bottom: 40px;">
        <div class="stats-card success">
            <div class="stats-label"><?= $_SESSION['lang'] === 'fr' ? 'Correct' : ($_SESSION['lang'] === 'rw' ? 'Byizana' : 'Correct') ?></div>
            <div class="stats-value"><?= $session['score'] ?>/20</div>
        </div>
        <div class="stats-card danger">
            <div class="stats-label"><?= $_SESSION['lang'] === 'fr' ? 'Incorrect' : ($_SESSION['lang'] === 'rw' ? 'Ntabwo byizana' : 'Incorrect') ?></div>
            <div class="stats-value"><?= 20 - $session['score'] ?>/20</div>
        </div>
        <div class="stats-card">
            <div class="stats-label"><?= $_SESSION['lang'] === 'fr' ? 'Pourcentage' : ($_SESSION['lang'] === 'rw' ? 'Ijanisha' : 'Percentage') ?></div>
            <div class="stats-value"><?= round(($session['score'] / 20) * 100) ?>%</div>
        </div>
    </div>

    <!-- Actions -->
    <div style="text-align: center; margin-bottom: 40px;">
        <a href="<?= SITE_URL ?>/test" class="btn btn-primary"><?= Translate::t('test_take_another') ?></a>
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary"><?= Translate::t('test_back_dashboard') ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
