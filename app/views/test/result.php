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
            <?= $passed ? '✓ PASSED' : '✗ NOT PASSED' ?>
        </div>
        <p style="margin: 10px 0; color: #666;">
            You got <?= $session['score'] ?> out of 20 questions correct
            (<?= round(($session['score'] / 20) * 100) ?>%)
        </p>
        <p style="color: #666; font-size: 14px;">
            <?php if ($passed): ?>
                Great job! You have passed the test.
            <?php else: ?>
                You need <?= ($pass_score - $session['score']) ?> more correct answers to pass (<?= $pass_score ?>/20).
            <?php endif; ?>
        </p>
    </div>

    <!-- Answer Review -->
    <h2 style="margin: 40px 0 20px;">📋 Answer Review</h2>
    
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
                    <p><strong>Your answer:</strong> <?= htmlspecialchars($answer['user_answer']) ?? 'No answer' ?></p>
                    <?php if (!$answer['is_correct']): ?>
                        <p><strong>Correct answer:</strong> <span style="color: var(--success);">You should select the correct answer</span></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Summary -->
    <div style="display: grid; grid-template-columns: repeatauto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 40px; margin-bottom: 40px;">
        <div class="stats-card success">
            <div class="stats-label">Correct</div>
            <div class="stats-value"><?= $session['score'] ?>/20</div>
        </div>
        <div class="stats-card danger">
            <div class="stats-label">Incorrect</div>
            <div class="stats-value"><?= 20 - $session['score'] ?>/20</div>
        </div>
        <div class="stats-card">
            <div class="stats-label">Percentage</div>
            <div class="stats-value"><?= round(($session['score'] / 20) * 100) ?>%</div>
        </div>
    </div>

    <!-- Actions -->
    <div style="text-align: center; margin-bottom: 40px;">
        <a href="<?= SITE_URL ?>/test" class="btn btn-primary">Take Another Test</a>
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
