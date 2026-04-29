<?php
ob_start();
$totalQuestions = count(json_decode($session['questions'] ?? '[]', true) ?: []);
$totalQuestions = $totalQuestions > 0 ? $totalQuestions : max(1, count($answers));
$score = (int) $session['score'];
$incorrect = max(0, $totalQuestions - $score);
$percent = round(($score / $totalQuestions) * 100);
$noAnswerLabel = Translate::t('test_no_answer');
$yourAnswerLabel = Translate::t('test_your_answer');
$correctAnswerLabel = Translate::t('test_correct_answer');
$blankLabel = Translate::t('test_blank');
$correctLabel = Translate::t('test_correct');
$incorrectLabel = Translate::t('test_incorrect');
?>

<style>
    .review-page {
        max-width: 1120px;
        margin: 0 auto;
        padding: 22px 20px 54px;
    }

    .review-hero {
        display: grid;
        grid-template-columns: 220px minmax(0, 1fr);
        gap: 24px;
        align-items: center;
        background: #fff;
        border: 1px solid #d9e2f2;
        border-radius: 8px;
        padding: 26px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
        margin-bottom: 22px;
    }

    .review-score-ring {
        width: 174px;
        height: 174px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        margin: 0 auto;
        background:
            radial-gradient(circle at center, #fff 0 58%, transparent 59%),
            conic-gradient(<?= $passed ? '#159a89' : '#d92d20' ?> <?= $percent ?>%, #e8eef7 0);
    }

    .review-score-ring strong {
        display: block;
        font-size: 34px;
        line-height: 1;
        color: #172033;
        text-align: center;
    }

    .review-score-ring span {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 800;
        margin-top: 6px;
        text-align: center;
    }

    .review-title {
        font-size: 28px;
        line-height: 1.2;
        margin: 0 0 8px;
        color: #172033;
        font-weight: 900;
    }

    .review-subtitle {
        color: #64748b;
        margin: 0 0 18px;
    }

    .review-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .review-stat {
        border: 1px solid #d9e2f2;
        border-radius: 8px;
        background: #f8fbff;
        padding: 14px;
    }

    .review-stat span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .review-stat strong {
        display: block;
        color: #172033;
        font-size: 26px;
        line-height: 1;
    }

    .review-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 26px 0 14px;
    }

    .review-section-title h2 {
        margin: 0;
        font-size: 22px;
        color: #172033;
    }

    .review-section-title span {
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
    }

    .review-list {
        display: grid;
        gap: 14px;
    }

    .review-card {
        background: #fff;
        border: 1px solid #d9e2f2;
        border-left-width: 5px;
        border-radius: 8px;
        padding: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
    }

    .review-card.correct {
        border-left-color: #159a89;
    }

    .review-card.incorrect {
        border-left-color: #d92d20;
    }

    .review-card.blank {
        border-left-color: #b7791f;
    }

    .review-card-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .review-question-no {
        color: #64748b;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .review-badge {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 900;
    }

    .review-badge.correct {
        background: #e9f8f5;
        color: #127a6d;
    }

    .review-badge.incorrect {
        background: #fff1ef;
        color: #b42318;
    }

    .review-badge.blank {
        background: #fff8e8;
        color: #8a5a12;
    }

    .review-question {
        color: #172033;
        font-size: 18px;
        line-height: 1.45;
        font-weight: 800;
        margin-bottom: 14px;
    }

    .review-answer-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .review-answer-box {
        border: 1px solid #d9e2f2;
        border-radius: 8px;
        background: #f8fbff;
        padding: 13px;
    }

    .review-answer-box.user.correct {
        background: #e9f8f5;
        border-color: rgba(21, 154, 137, 0.32);
    }

    .review-answer-box.user.incorrect {
        background: #fff1ef;
        border-color: rgba(217, 45, 32, 0.26);
    }

    .review-answer-box.user.blank {
        background: #fff8e8;
        border-color: rgba(183, 121, 31, 0.24);
    }

    .review-answer-box.correct-answer {
        background: #e9f8f5;
        border-color: rgba(21, 154, 137, 0.32);
    }

    .review-answer-box span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .review-answer-box p {
        margin: 0;
        color: #172033;
        font-weight: 750;
        line-height: 1.45;
    }

    .review-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin: 28px 0 10px;
    }

    @media (max-width: 760px) {
        .review-hero,
        .review-answer-grid {
            grid-template-columns: 1fr;
        }

        .review-summary {
            grid-template-columns: 1fr;
        }

        .review-card-head,
        .review-section-title,
        .review-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .review-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="review-page">
    <section class="review-hero">
        <div class="review-score-ring">
            <div>
                <strong><?= $percent ?>%</strong>
                <span><?= $score ?>/<?= $totalQuestions ?></span>
            </div>
        </div>
        <div>
            <h1 class="review-title"><?= $passed ? Translate::t('test_you_passed') : Translate::t('test_you_not_passed') ?></h1>
            <p class="review-subtitle">
                <?= $passed ? Translate::t('test_result_pass_subtitle') : Translate::t('test_result_fail_subtitle') ?>
            </p>
            <div class="review-summary">
                <div class="review-stat">
                    <span><?= $correctLabel ?></span>
                    <strong><?= $score ?></strong>
                </div>
                <div class="review-stat">
                    <span><?= $incorrectLabel ?></span>
                    <strong><?= $incorrect ?></strong>
                </div>
                <div class="review-stat">
                    <span><?= Translate::t('test_pass_mark') ?></span>
                    <strong><?= $pass_score ?>/<?= $totalQuestions ?></strong>
                </div>
            </div>
        </div>
    </section>

    <div class="review-section-title">
        <h2><?= Translate::t('test_answer_review') ?></h2>
        <span><?= $totalQuestions ?> <?= Translate::t('test_questions_count') ?></span>
    </div>

    <div class="review-list">
        <?php foreach ($answers as $idx => $answer): ?>
            <?php
                $isCorrect = (bool) $answer['is_correct'];
                $isBlank = empty($answer['selected_answer_id']);
                $state = $isCorrect ? 'correct' : ($isBlank ? 'blank' : 'incorrect');
                $badgeText = $isCorrect ? $correctLabel : ($isBlank ? $blankLabel : $incorrectLabel);
                $userAnswer = $isBlank ? $noAnswerLabel : ($answer['user_answer'] ?: $noAnswerLabel);
                $correctAnswer = $answer['correct_answer'] ?: Translate::t('test_correct_answer_unavailable');
            ?>
            <article class="review-card <?= $state ?>">
                <div class="review-card-head">
                    <div class="review-question-no"><?= Translate::t('test_question') ?> <?= $idx + 1 ?></div>
                    <div class="review-badge <?= $state ?>"><?= $badgeText ?></div>
                </div>

                <div class="review-question">
                    <?= htmlspecialchars($answer['question_text'] ?? '') ?>
                </div>

                <div class="review-answer-grid">
                    <div class="review-answer-box user <?= $state ?>">
                        <span><?= $yourAnswerLabel ?></span>
                        <p><?= htmlspecialchars($userAnswer) ?></p>
                    </div>
                    <div class="review-answer-box correct-answer">
                        <span><?= $correctAnswerLabel ?></span>
                        <p><?= htmlspecialchars($correctAnswer) ?></p>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="review-actions">
        <a href="<?= SITE_URL ?>/test" class="btn btn-primary"><?= Translate::t('test_take_another') ?></a>
        <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary"><?= Translate::t('test_back_dashboard') ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
