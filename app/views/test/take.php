<?php
ob_start();
?>

<div class="container">
    <style>
        .question-image {
            max-width: 100%;
            height: auto;
            max-height: 300px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: block;
        }
    </style>
    
    <div class="test-header">
        <div class="test-info">
            <div class="test-info-item">
                <div class="test-info-label"><?= Translate::t('test_question') ?></div>
                <div class="test-info-value"><span id="current-question">1</span> / <?= $total_questions ?></div>
            </div>
            <div class="test-info-item">
                <div class="test-info-label"><?= Translate::t('test_answered') ?></div>
                <div class="test-info-value"><span id="answered-count">0</span> / <?= $total_questions ?></div>
            </div>
        </div>
        <div>
            <div class="test-info-label"><?= Translate::t('test_time_remaining') ?></div>
            <div id="timer" class="timer"><?php 
                $mins = floor($remaining_time / 60);
                $secs = $remaining_time % 60;
                echo sprintf('%d:%02d', $mins, $secs);
            ?></div>
        </div>
    </div>

    <form id="testForm" method="POST" action="<?= SITE_URL ?>/test/submit" onsubmit="return onTestSubmit()">
        <?php foreach ($questions as $idx => $question): ?>
            <div class="question-container" data-question-id="<?= $question['id'] ?>" style="display: <?= $idx === 0 ? 'block' : 'none' ?>;" id="question-<?= $idx ?>">
                <div class="question-number">Question <?= $idx + 1 ?> of <?= $total_questions ?></div>
                <div class="question-text"><?= htmlspecialchars($question['text']) ?></div>
                
                <?php if ($question['image']): ?>
                    <img src="<?= SITE_URL ?>/assets/images/<?= htmlspecialchars($question['image']) ?>" alt="Question Image" class="question-image" onerror="this.style.display='none'">
                <?php endif; ?>

                <div class="answer-options">
                    <?php foreach ($question['answers'] as $aid => $answer): ?>
                        <label class="answer-option">
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= $answer['id'] ?>" required>
                            <label><?= htmlspecialchars($answer['text']) ?></label>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div style="display: flex; gap: 10px; margin-top: 30px; justify-content: center;">
            <button type="button" id="prev-btn" class="btn btn-secondary" onclick="previousQuestion()"><?= Translate::t('test_previous') ?></button>
            <button type="button" id="next-btn" class="btn btn-primary" onclick="nextQuestion()"><?= Translate::t('test_next') ?></button>
            <button type="submit" id="submit-btn" class="btn btn-success hidden"><?= Translate::t('test_submit_test') ?></button>
        </div>
    </form>
</div>

<script>
let currentQuestion = 0;
const totalQuestions = <?= $total_questions ?>;
const testForm = document.getElementById('testForm');

function showQuestion(index) {
    // Hide all questions
    for (let i = 0; i < totalQuestions; i++) {
        document.getElementById('question-' + i).style.display = 'none';
    }
    
    // Show current question
    document.getElementById('question-' + index).style.display = 'block';
    document.getElementById('current-question').innerText = index + 1;
    
    // Update button visibility
    document.getElementById('prev-btn').style.display = index > 0 ? 'block' : 'none';
    document.getElementById('next-btn').style.display = index < totalQuestions - 1 ? 'block' : 'none';
    document.getElementById('submit-btn').style.display = index === totalQuestions - 1 ? 'block' : 'none';
    
    // Update answered count
    updateAnsweredCount();
    
    // Scroll to top
    window.scrollTo(0, 0);
}

function nextQuestion() {
    if (currentQuestion < totalQuestions - 1) {
        currentQuestion++;
        showQuestion(currentQuestion);
    }
}

function previousQuestion() {
    if (currentQuestion > 0) {
        currentQuestion--;
        showQuestion(currentQuestion);
    }
}

function updateAnsweredCount() {
    let count = 0;
    for (let i = 0; i < totalQuestions; i++) {
        const answered = document.querySelector('#question-' + i + ' input[type="radio"]:checked');
        if (answered) count++;
    }
    document.getElementById('answered-count').innerText = count;
}

// Allow keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowRight') nextQuestion();
    if (e.key === 'ArrowLeft') previousQuestion();
});

// Update answered count on radio change
testForm.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', updateAnsweredCount);
});

// Show first question
showQuestion(0);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
