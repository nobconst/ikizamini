<?php
ob_start();
$examText = [
  'answered' => Translate::t('test_answered'),
  'blank' => Translate::t('test_blank'),
  'question' => Translate::t('test_question'),
  'review' => Translate::t('test_review'),
  'next' => Translate::t('test_next'),
  'unansweredZeroNote' => Translate::t('test_unanswered_zero_note'),
  'submitAnytimeNote' => Translate::t('test_submit_anytime_note'),
  'allAnsweredNotice' => Translate::t('test_all_answered_notice'),
  'blankSubmitNotice' => Translate::t('test_blank_submit_notice'),
  'blankConfirmSuffix' => Translate::t('test_blank_confirm_suffix'),
  'submitNowConfirm' => Translate::t('test_submit_now_confirm'),
  'timeExpired' => Translate::t('test_time_expired'),
  'submittingExam' => Translate::t('test_submitting_exam'),
  'savingAnswers' => Translate::t('test_saving_answers'),
  'savingBlanksZero' => Translate::t('test_saving_blanks_zero'),
  'leavingWarning' => Translate::t('test_leaving_warning')
];
?>

<style>
  .exam-shell {
    --exam-ink: #172033;
    --exam-muted: #64748b;
    --exam-line: #d9e2f2;
    --exam-panel: #ffffff;
    --exam-soft: #f5f8fc;
    --exam-primary: #0f6bbf;
    --exam-accent: #159a89;
    --exam-danger: #d92d20;
    --exam-warning: #b7791f;
    color: var(--exam-ink);
    background: #f3f7fb;
    margin: -20px 0 0;
    min-height: calc(100vh - 76px);
  }

  .exam-topbar {
    position: sticky;
    top: 76px;
    z-index: 80;
    background: rgba(255, 255, 255, 0.96);
    border-bottom: 1px solid var(--exam-line);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
  }

  .exam-topbar-inner {
    max-width: 1180px;
    margin: 0 auto;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .exam-title-block h1 {
    font-size: 20px;
    line-height: 1.2;
    margin: 0 0 4px;
    font-weight: 800;
    color: var(--exam-ink);
  }

  .exam-title-block p {
    margin: 0;
    color: var(--exam-muted);
    font-size: 14px;
  }

  .exam-timer {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 126px;
    height: 44px;
    padding: 0 16px;
    border: 1px solid rgba(15, 107, 191, 0.22);
    border-radius: 8px;
    background: #eef7ff;
    color: var(--exam-primary);
    font-size: 18px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
  }

  .exam-timer.warning {
    background: #fff5f3;
    border-color: rgba(217, 45, 32, 0.24);
    color: var(--exam-danger);
  }

  .exam-wrap {
    max-width: 1180px;
    margin: 0 auto;
    padding: 28px 20px 56px;
    display: grid;
    grid-template-columns: minmax(0, 1fr) 290px;
    gap: 24px;
    align-items: start;
  }

  .exam-main,
  .exam-side {
    background: var(--exam-panel);
    border: 1px solid var(--exam-line);
    border-radius: 8px;
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
  }

  .exam-main {
    padding: 26px;
  }

  .exam-side {
    position: sticky;
    top: 158px;
    padding: 18px;
  }

  .exam-progress {
    height: 8px;
    border-radius: 999px;
    background: #e8eef7;
    overflow: hidden;
    margin-bottom: 12px;
  }

  .exam-progress-fill {
    height: 100%;
    width: 0;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--exam-primary), var(--exam-accent));
    transition: width 0.2s ease;
  }

  .exam-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    color: var(--exam-muted);
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 22px;
  }

  .exam-question {
    display: none;
  }

  .exam-question.active {
    display: block;
  }

  .exam-kicker {
    display: inline-flex;
    align-items: center;
    min-height: 30px;
    padding: 0 10px;
    border-radius: 8px;
    background: #eef7ff;
    color: var(--exam-primary);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 14px;
  }

  .exam-question-title {
    color: var(--exam-muted);
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 10px;
  }

  .exam-text {
    font-size: 22px;
    line-height: 1.45;
    font-weight: 800;
    margin-bottom: 22px;
  }

  .exam-image {
    max-width: 100%;
    max-height: 280px;
    object-fit: contain;
    display: block;
    margin: 0 0 22px;
    border: 1px solid var(--exam-line);
    border-radius: 8px;
    background: var(--exam-soft);
  }

  .exam-options {
    display: grid;
    gap: 12px;
  }

  .exam-option {
    width: 100%;
    min-height: 58px;
    display: grid;
    grid-template-columns: 38px minmax(0, 1fr);
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border: 1px solid var(--exam-line);
    border-radius: 8px;
    background: var(--exam-soft);
    color: var(--exam-ink);
    text-align: left;
    cursor: pointer;
    transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
  }

  .exam-option:hover {
    background: #eef7ff;
    border-color: rgba(15, 107, 191, 0.42);
  }

  .exam-option.selected {
    background: #e9f8f5;
    border-color: var(--exam-accent);
    box-shadow: 0 0 0 3px rgba(21, 154, 137, 0.1);
  }

  .exam-letter {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid var(--exam-line);
    color: var(--exam-muted);
    font-weight: 900;
  }

  .exam-option.selected .exam-letter {
    background: var(--exam-accent);
    border-color: var(--exam-accent);
    color: #fff;
  }

  .exam-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-top: 24px;
  }

  .exam-btn {
    min-height: 44px;
    padding: 0 18px;
    border-radius: 8px;
    border: 1px solid transparent;
    font-weight: 800;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
  }

  .exam-btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
  }

  .exam-btn-primary {
    background: var(--exam-primary);
    color: #fff;
    box-shadow: 0 8px 18px rgba(15, 107, 191, 0.22);
  }

  .exam-btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 10px 22px rgba(15, 107, 191, 0.26);
  }

  .exam-btn-secondary {
    background: #fff;
    color: var(--exam-primary);
    border-color: rgba(15, 107, 191, 0.24);
  }

  .exam-btn-danger {
    margin-left: auto;
    background: #fff;
    color: var(--exam-danger);
    border-color: rgba(217, 45, 32, 0.28);
  }

  .exam-side-title {
    font-size: 14px;
    font-weight: 900;
    color: var(--exam-ink);
    margin-bottom: 10px;
  }

  .exam-side-copy {
    color: var(--exam-muted);
    font-size: 13px;
    line-height: 1.5;
    margin-bottom: 16px;
  }

  .exam-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 16px;
  }

  .exam-stat {
    background: var(--exam-soft);
    border: 1px solid var(--exam-line);
    border-radius: 8px;
    padding: 12px;
  }

  .exam-stat-label {
    color: var(--exam-muted);
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 4px;
  }

  .exam-stat-value {
    color: var(--exam-ink);
    font-size: 22px;
    font-weight: 900;
  }

  .exam-map {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
  }

  .exam-dot {
    aspect-ratio: 1;
    border-radius: 8px;
    border: 1px solid var(--exam-line);
    background: #fff;
    color: var(--exam-muted);
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
  }

  .exam-dot.active {
    background: var(--exam-primary);
    border-color: var(--exam-primary);
    color: #fff;
  }

  .exam-dot.answered {
    background: #e9f8f5;
    border-color: rgba(21, 154, 137, 0.42);
    color: var(--exam-accent);
  }

  .exam-dot.active.answered {
    color: #fff;
  }

  .exam-notice {
    display: none;
    margin-top: 16px;
    padding: 12px;
    border-radius: 8px;
    background: #fff8e8;
    border: 1px solid rgba(183, 121, 31, 0.22);
    color: var(--exam-warning);
    font-size: 13px;
    font-weight: 700;
  }

  .exam-notice.show {
    display: block;
  }

  .exam-submitting {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    background: rgba(15, 23, 42, 0.58);
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .exam-submitting.show {
    display: flex;
  }

  .exam-submitting-box {
    width: min(420px, 100%);
    border-radius: 8px;
    background: #fff;
    padding: 24px;
    text-align: center;
    box-shadow: 0 22px 48px rgba(15, 23, 42, 0.26);
  }

  .exam-submitting-box h2 {
    font-size: 20px;
    margin-bottom: 8px;
  }

  .exam-submitting-box p {
    color: var(--exam-muted);
    margin: 0;
  }

  @media (max-width: 900px) {
    .exam-topbar {
      top: 68px;
    }

    .exam-wrap {
      grid-template-columns: 1fr;
    }

    .exam-side {
      position: static;
      order: -1;
    }

    .exam-map {
      grid-template-columns: repeat(10, 1fr);
    }
  }

  @media (max-width: 560px) {
    .exam-shell {
      margin-top: -20px;
    }

    .exam-topbar-inner {
      align-items: flex-start;
      flex-direction: column;
    }

    .exam-timer {
      width: 100%;
    }

    .exam-main {
      padding: 18px;
    }

    .exam-text {
      font-size: 18px;
    }

    .exam-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    .exam-btn-danger {
      grid-column: 1 / -1;
      margin-left: 0;
    }
  }
</style>

<div class="exam-shell">
  <div class="exam-topbar">
    <div class="exam-topbar-inner">
      <div class="exam-title-block">
        <h1><?= Translate::t('test_driving_test') ?></h1>
        <p><?= Translate::t('test_unanswered_zero_note') ?></p>
      </div>
      <div class="exam-timer" id="examTimer" aria-live="polite">
        <?php
          $mins = floor($remaining_time / 60);
          $secs = $remaining_time % 60;
          echo sprintf('%02d:%02d', $mins, $secs);
        ?>
      </div>
    </div>
  </div>

  <div class="exam-wrap">
    <section class="exam-main" aria-label="<?= Translate::t('test_driving_test') ?>">
      <div class="exam-progress">
        <div class="exam-progress-fill" id="examProgress"></div>
      </div>
      <div class="exam-meta">
        <span id="examAnswered">0 / <?= $total_questions ?> <?= Translate::t('test_answered') ?></span>
        <span id="examPosition"><?= Translate::t('test_question') ?> 1 / <?= $total_questions ?></span>
      </div>

      <form id="testForm" method="POST" action="<?= SITE_URL ?>/test/submit" onsubmit="return window.examBeforeSubmit ? window.examBeforeSubmit() : true">
        <input type="hidden" name="submit_reason" id="submitReason" value="manual">

        <?php foreach ($questions as $idx => $question): ?>
          <article class="exam-question <?= $idx === 0 ? 'active' : '' ?>" data-question-index="<?= $idx ?>" data-question-id="<?= (int) $question['id'] ?>" id="question-<?= $idx ?>">
            <div class="exam-kicker"><?= Translate::t('test_question') ?></div>
            <div class="exam-question-title"><?= Translate::t('test_question') ?> <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?> / <?= $total_questions ?></div>

            <?php if (!empty($question['image'])): ?>
              <img src="<?= SITE_URL ?>/assets/images/questions/<?= htmlspecialchars($question['image']) ?>" alt="<?= Translate::t('test_question') ?>" class="exam-image" onerror="this.style.display='none'">
            <?php endif; ?>

            <div class="exam-text"><?= htmlspecialchars($question['text']) ?></div>
            <div class="exam-options">
              <?php
                $letters = ['A', 'B', 'C', 'D'];
                foreach ($question['answers'] as $answerIdx => $answer):
              ?>
                <button type="button" class="exam-option" data-question="<?= (int) $question['id'] ?>" data-answer="<?= (int) $answer['id'] ?>">
                  <span class="exam-letter"><?= $letters[$answerIdx] ?? '' ?></span>
                  <span><?= htmlspecialchars($answer['text']) ?></span>
                  <input type="radio" name="answers[<?= (int) $question['id'] ?>]" value="<?= (int) $answer['id'] ?>" hidden>
                </button>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>

        <div class="exam-actions">
          <button type="button" class="exam-btn exam-btn-secondary" id="examPrev"><?= Translate::t('test_previous') ?></button>
          <button type="button" class="exam-btn exam-btn-primary" id="examNext"><?= Translate::t('test_next') ?></button>
          <button type="button" class="exam-btn exam-btn-danger" id="examSubmit"><?= Translate::t('test_submit_test') ?></button>
        </div>
      </form>
    </section>

    <aside class="exam-side" aria-label="Exam status">
      <div class="exam-side-title"><?= Translate::t('test_exam_status') ?></div>
      <div class="exam-side-copy"><?= Translate::t('test_submit_anytime_note') ?></div>

      <div class="exam-stats">
        <div class="exam-stat">
          <div class="exam-stat-label"><?= Translate::t('test_answered') ?></div>
          <div class="exam-stat-value" id="examAnsweredStat">0</div>
        </div>
        <div class="exam-stat">
          <div class="exam-stat-label"><?= Translate::t('test_blank') ?></div>
          <div class="exam-stat-value" id="examBlankStat"><?= $total_questions ?></div>
        </div>
      </div>

      <div class="exam-map" id="examMap"></div>
      <div class="exam-notice" id="examNotice"></div>
    </aside>
  </div>
</div>

<div class="exam-submitting" id="examSubmitting" role="status" aria-live="assertive">
  <div class="exam-submitting-box">
    <h2 id="examSubmittingTitle"><?= Translate::t('test_submitting_exam') ?></h2>
    <p id="examSubmittingText"><?= Translate::t('test_saving_answers') ?></p>
  </div>
</div>

<script>
(function () {
  const totalQuestions = <?= (int) $total_questions ?>;
  const form = document.getElementById('testForm');
  const timerEl = document.getElementById('examTimer');
  const progressEl = document.getElementById('examProgress');
  const answeredEl = document.getElementById('examAnswered');
  const positionEl = document.getElementById('examPosition');
  const answeredStatEl = document.getElementById('examAnsweredStat');
  const blankStatEl = document.getElementById('examBlankStat');
  const mapEl = document.getElementById('examMap');
  const noticeEl = document.getElementById('examNotice');
  const submittingEl = document.getElementById('examSubmitting');
  const submittingTitleEl = document.getElementById('examSubmittingTitle');
  const submittingTextEl = document.getElementById('examSubmittingText');
  const submitReasonEl = document.getElementById('submitReason');
  const testStorageKey = 'test_answers_<?= (int) $test_id ?>';
  const i18n = <?= json_encode($examText, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  const questions = Array.from(document.querySelectorAll('.exam-question'));
  const answers = new Array(totalQuestions).fill(null);
  let current = 0;
  let timeLeft = <?= (int) $remaining_time ?>;
  let submitting = false;

  function formatTime(seconds) {
    const safeSeconds = Math.max(0, seconds);
    const minutes = Math.floor(safeSeconds / 60);
    const secs = safeSeconds % 60;
    return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
  }

  function answeredCount() {
    return answers.filter(Boolean).length;
  }

  function buildMap() {
    mapEl.innerHTML = '';
    answers.forEach((answer, index) => {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'exam-dot' + (index === current ? ' active' : '') + (answer ? ' answered' : '');
      button.textContent = index + 1;
      button.addEventListener('click', () => {
        current = index;
        render();
      });
      mapEl.appendChild(button);
    });
  }

  function render() {
    questions.forEach((question, index) => {
      question.classList.toggle('active', index === current);
    });

    const answered = answeredCount();
    const blank = totalQuestions - answered;
    progressEl.style.width = `${(answered / totalQuestions) * 100}%`;
    answeredEl.textContent = `${answered} / ${totalQuestions} ${i18n.answered}`;
    positionEl.textContent = `${i18n.question} ${current + 1} / ${totalQuestions}`;
    answeredStatEl.textContent = answered;
    blankStatEl.textContent = blank;
    document.getElementById('examPrev').disabled = current === 0;
    document.getElementById('examNext').textContent = current === totalQuestions - 1 ? i18n.review : i18n.next;
    buildMap();
  }

  function chooseOption(button) {
    const question = button.closest('.exam-question');
    const index = Number(question.dataset.questionIndex);
    const radio = button.querySelector('input[type="radio"]');

    question.querySelectorAll('.exam-option').forEach(option => option.classList.remove('selected'));
    button.classList.add('selected');
    radio.checked = true;
    answers[index] = radio.value;
    noticeEl.classList.remove('show');
    render();
  }

  function move(step) {
    const next = current + step;
    if (next < 0) return;
    if (next >= totalQuestions) {
      showSubmitNotice();
      return;
    }
    current = next;
    render();
  }

  function showSubmitNotice() {
    const blank = totalQuestions - answeredCount();
    noticeEl.textContent = blank > 0
      ? `${blank} ${i18n.blank}. ${i18n.blankSubmitNotice}`
      : i18n.allAnsweredNotice;
    noticeEl.classList.add('show');
  }

  function submitExam(reason) {
    if (submitting) return;

    const blank = totalQuestions - answeredCount();
    if (reason === 'manual' && blank > 0) {
      const ok = confirm(`${blank} ${i18n.blank}. ${i18n.submitNowConfirm} ${i18n.blankConfirmSuffix}`);
      if (!ok) {
        showSubmitNotice();
        return;
      }
    } else if (reason === 'manual' && !confirm(i18n.submitNowConfirm)) {
      return;
    }

    submitting = true;
    submitReasonEl.value = reason;
    submittingTitleEl.textContent = reason === 'time_expired' ? i18n.timeExpired : i18n.submittingExam;
    submittingTextEl.textContent = blank > 0
      ? i18n.savingBlanksZero
      : i18n.savingAnswers;
    submittingEl.classList.add('show');
    localStorage.removeItem(testStorageKey);
    form.submit();
  }

  window.examBeforeSubmit = function () {
    return true;
  };

  document.getElementById('examPrev').addEventListener('click', () => move(-1));
  document.getElementById('examNext').addEventListener('click', () => move(1));
  document.getElementById('examSubmit').addEventListener('click', () => submitExam('manual'));

  document.querySelectorAll('.exam-option').forEach(button => {
    button.addEventListener('click', () => chooseOption(button));
  });

  const timer = setInterval(() => {
    timeLeft -= 1;
    timerEl.textContent = formatTime(timeLeft);
    if (timeLeft <= 300) timerEl.classList.add('warning');
    if (timeLeft <= 0) {
      clearInterval(timer);
      submitExam('time_expired');
    }
  }, 1000);

  window.addEventListener('beforeunload', function (event) {
    if (submitting) return;
    event.preventDefault();
    event.returnValue = i18n.leavingWarning;
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowLeft') move(-1);
    if (event.key === 'ArrowRight') move(1);
  });

  if (timeLeft <= 300) timerEl.classList.add('warning');
  render();
})();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
