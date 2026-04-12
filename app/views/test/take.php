<?php
ob_start();
?>

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f4f7ff;
    color: #1a2340;
    min-height: 100vh;
  }

  header {
    background: #fff;
    border-bottom: 2px solid #e0e8ff;
    padding: 0 40px;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 12px rgba(74,111,220,0.07);
  }

  .logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    font-size: 1.25rem;
    color: #2a55d6;
    letter-spacing: -0.02em;
  }

  .logo-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, #4a6fdc, #2a55d6);
    border-radius: 10px;
    display: grid; place-items: center;
    font-size: 18px;
    box-shadow: 0 4px 10px rgba(74,111,220,0.3);
  }

  .timer-pill {
    display: flex; align-items: center; gap: 8px;
    background: #eef2ff;
    border: 1.5px solid #c7d4f8;
    padding: 8px 20px;
    border-radius: 100px;
    font-weight: 700; font-size: 1rem;
    color: #2a55d6;
  }

  .timer-pill.warning { background: #fff0f0; border-color: #ffb3b3; color: #e03e3e; }

  .timer-dot {
    width: 8px; height: 8px;
    background: #2a55d6;
    border-radius: 50%;
    animation: blink 1.4s infinite;
  }
  .timer-pill.warning .timer-dot { background: #e03e3e; }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

  .page {
    max-width: 780px;
    margin: 0 auto;
    padding: 44px 20px 80px;
  }

  .test-heading { text-align: center; margin-bottom: 36px; }
  .test-heading h1 { font-size: 1.6rem; font-weight: 800; color: #1a2340; margin-bottom: 6px; }
  .test-heading p { color: #6b7aaa; font-size: 0.95rem; }

  .progress-bar-wrap { background: #e0e8ff; height: 8px; border-radius: 100px; margin-bottom: 12px; overflow: hidden; }
  .progress-bar-fill { height: 100%; background: linear-gradient(90deg, #4a6fdc, #2a55d6); border-radius: 100px; transition: width 0.5s ease; }

  .progress-meta { display: flex; justify-content: space-between; font-size: 0.82rem; color: #6b7aaa; font-weight: 600; margin-bottom: 28px; }

  .dots-row { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 32px; }

  .dot {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1.5px solid #c7d4f8;
    background: #fff;
    font-size: 0.75rem; font-weight: 700;
    color: #a0aed0;
    display: grid; place-items: center;
    cursor: pointer;
    transition: all 0.15s;
  }
  .dot:hover { border-color: #4a6fdc; color: #4a6fdc; }
  .dot.active { background: #2a55d6; border-color: #2a55d6; color: #fff; }
  .dot.answered { border-color: #4a6fdc; color: #2a55d6; background: #eef2ff; }
  .dot.answered.active { background: #2a55d6; color: #fff; }

  .q-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid #e0e8ff;
    padding: 40px 40px 36px;
    margin-bottom: 24px;
    box-shadow: 0 4px 24px rgba(74,111,220,0.06);
    animation: fadeUp 0.35s ease;
  }
  @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

  .q-category-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eef2ff; border: 1px solid #c7d4f8;
    color: #2a55d6;
    padding: 5px 14px; border-radius: 100px;
    font-size: 0.75rem; font-weight: 700;
    letter-spacing: 0.04em; text-transform: uppercase;
    margin-bottom: 20px;
  }

  .q-number { font-size: 0.78rem; font-weight: 600; color: #a0aed0; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 10px; }

  .sign-box {
    width: 90px; height: 90px; border-radius: 14px;
    background: #f4f7ff; border: 1.5px solid #e0e8ff;
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; margin-bottom: 20px;
  }

  .question-image {
    max-width: 100%;
    height: auto;
    max-height: 300px;
    margin: 15px 0;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: block;
  }

  .q-text { font-size: 1.2rem; font-weight: 700; line-height: 1.55; color: #1a2340; margin-bottom: 28px; }

  .options { display: flex; flex-direction: column; gap: 12px; }

  .option-btn {
    width: 100%; display: flex; align-items: center; gap: 14px;
    padding: 16px 20px;
    background: #f4f7ff;
    border: 1.5px solid #e0e8ff;
    border-radius: 12px;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.95rem; font-weight: 500;
    color: #1a2340; text-align: left;
    transition: all 0.18s;
  }
  .option-btn:hover:not(:disabled) { border-color: #4a6fdc; background: #eef2ff; transform: translateX(4px); }
  .option-btn.selected { border-color: #2a55d6; background: #eef2ff; }

  .option-letter {
    width: 34px; height: 34px; border-radius: 8px;
    background: #fff; border: 1.5px solid #c7d4f8;
    display: grid; place-items: center;
    font-weight: 800; font-size: 0.8rem; color: #a0aed0;
    flex-shrink: 0; transition: all 0.18s;
  }
  .option-btn.selected .option-letter { background: #2a55d6; border-color: #2a55d6; color: #fff; }

  .nav-row { display: flex; align-items: center; gap: 12px; }

  .btn {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700; font-size: 0.9rem;
    padding: 13px 26px; border-radius: 10px;
    border: none; cursor: pointer;
    transition: all 0.18s;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn:disabled { opacity: 0.4; cursor: not-allowed; }

  .btn-outline { background: #fff; color: #2a55d6; border: 1.5px solid #c7d4f8; }
  .btn-outline:hover:not(:disabled) { border-color: #2a55d6; background: #eef2ff; }

  .btn-blue { background: linear-gradient(135deg, #4a6fdc, #2a55d6); color: #fff; box-shadow: 0 4px 14px rgba(74,111,220,0.3); }
  .btn-blue:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(74,111,220,0.35); }

  .btn-submit { background: #fff; color: #e03e3e; border: 1.5px solid #ffb3b3; margin-left: auto; }
  .btn-submit:hover { background: #fff2f2; }

  .warn-badge {
    display: none; align-items: center; gap: 8px;
    background: #fff8e1; border: 1px solid #ffe082; border-radius: 10px;
    padding: 10px 16px; font-size: 0.85rem; color: #8a6500; font-weight: 600;
    margin-bottom: 20px;
  }
  .warn-badge.show { display: flex; }

  .notification {
    position: fixed;
    top: 80px;
    right: 20px;
    background: #fff;
    border: 1.5px solid #e0e8ff;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 4px 20px rgba(74,111,220,0.15);
    z-index: 1000;
    max-width: 350px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
  }

  .notification.show {
    transform: translateX(0);
  }

  .notification.warning {
    border-color: #ffb3b3;
    background: #fff8f8;
  }

  .notification.error {
    border-color: #e03e3e;
    background: #fff2f2;
  }

  .notification.info {
    border-color: #4a6fdc;
    background: #f0f4ff;
  }

  .notification-title {
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 4px;
    color: #1a2340;
  }

  .notification-message {
    font-size: 0.85rem;
    color: #6b7aaa;
    line-height: 1.4;
  }

  .notification-close {
    position: absolute;
    top: 12px;
    right: 12px;
    background: none;
    border: none;
    color: #a0aed0;
    cursor: pointer;
    font-size: 18px;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s;
  }

  .notification-close:hover {
    background: #f0f0f0;
    color: #6b7aaa;
  }

  .header-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
  }

  .driving-test-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #2a55d6;
    margin-bottom: 4px;
  }

  .test-instructions {
    font-size: 0.85rem;
    color: #6b7aaa;
  }

  @media (max-width: 600px) {
    header { padding: 0 16px; }
    .header-center { 
      position: static; 
      transform: none;
      margin-top: 10px;
    }
    .driving-test-title { font-size: 1.1rem; }
    .test-instructions { font-size: 0.8rem; }
    .page { padding: 28px 14px 60px; }
    .q-card { padding: 24px 18px; }
  }
</style>

<header>
  <div class="timer-pill" id="timer-pill">
    <div class="timer-dot"></div>
    <span id="timer-display"><?php 
        $mins = floor($remaining_time / 60);
        $secs = $remaining_time % 60;
        echo sprintf('%d:%02d', $mins, $secs);
    ?></span>
  </div>
  <div class="header-center">
    <div class="test-heading">
      <h1><?= Translate::t('test_driving_test') ?></h1>
      <p><?= Translate::t('test_answered') ?> all <?= $total_questions ?> questions. Your results will be shown after you submit.</p>
    </div>
  </div>
</header>

<!-- Notification Container -->
<div id="notification-container"></div>

<div class="page" id="quiz-page">

  

  <div class="progress-bar-wrap">
    <div class="progress-bar-fill" id="progress-fill" style="width:6.67%"></div>
  </div>
  <div class="progress-meta">
    <span id="answered-count">0 of <?= $total_questions ?> answered</span>
    <span id="q-of"><?= Translate::t('test_question') ?> 1 / <?= $total_questions ?></span>
  </div>

  <div class="dots-row" id="dots-row"></div>

  <div class="warn-badge" id="warn-badge">
    <span> Please answer all questions before submitting.</span>
  </div>

  <form id="testForm" method="POST" action="<?= SITE_URL ?>/test/submit" onsubmit="return onTestSubmit()">
    <?php foreach ($questions as $idx => $question): ?>
      <div class="q-card" data-question-id="<?= $question['id'] ?>" style="display: <?= $idx === 0 ? 'block' : 'none' ?>;" id="question-<?= $idx ?>">
        <div class="q-category-tag"><?= Translate::t('test_question') ?></div>
        <div class="q-number"><?= Translate::t('test_question') ?> <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?> / <?= $total_questions ?></div>
        
        <?php if ($question['image']): ?>
          <div class="sign-box">
            <img src="<?= SITE_URL ?>/assets/images/questions/<?= htmlspecialchars($question['image']) ?>" alt="Question Image" class="question-image" onerror="this.style.display='none'">
          </div>
        <?php endif; ?>
        
        <div class="q-text"><?= htmlspecialchars($question['text']) ?></div>
        <div class="options">
          <?php 
          $letters = ['A', 'B', 'C', 'D'];
          foreach ($question['answers'] as $aid => $answer): 
            $letterIndex = array_search($aid, array_keys($question['answers']));
          ?>
            <button type="button" class="option-btn" data-question="<?= $question['id'] ?>" data-answer="<?= $answer['id'] ?>" onclick="selectOption(this, <?= $question['id'] ?>, <?= $answer['id'] ?>)">
              <div class="option-letter"><?= $letters[$letterIndex] ?></div>
              <span><?= htmlspecialchars($answer['text']) ?></span>
              <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= $answer['id'] ?>" style="display: none;" required>
            </button>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="nav-row">
      <button type="button" class="btn btn-outline" id="btn-prev" onclick="go(-1)"><?= Translate::t('test_previous') ?></button>
      <button type="button" class="btn btn-submit" onclick="trySubmit()"><?= Translate::t('test_submit_test') ?></button>
      <button type="button" class="btn btn-blue" id="btn-next" onclick="go(1)"><?= Translate::t('test_next') ?></button>
    </div>
  </form>
</div>

<script>
let currentQuestion = 0;
const totalQuestions = <?= $total_questions ?>;
const testForm = document.getElementById('testForm');
let answers = new Array(totalQuestions).fill(null);
let timeLeft = <?= $remaining_time ?>;
let timerInt;

function init() { 
  buildDots(); 
  renderQ(); 
  startTimer(); 
  updateAnsweredCount();
}

function buildDots() {
  const row = document.getElementById('dots-row');
  row.innerHTML = '';
  for (let i = 0; i < totalQuestions; i++) {
    const d = document.createElement('button');
    d.className = 'dot' + (i===currentQuestion?' active':'') + (answers[i]!==null?' answered':'');
    d.textContent = i+1;
    d.onclick = () => { currentQuestion=i; renderQ(); };
    row.appendChild(d);
  }
}

function renderQ() {
  document.getElementById('warn-badge').classList.remove('show');
  
  // Hide all questions
  for (let i = 0; i < totalQuestions; i++) {
    document.getElementById('question-' + i).style.display = 'none';
  }
  
  // Show current question
  document.getElementById('question-' + currentQuestion).style.display = 'block';
  
  // Update progress
  const answered = answers.filter(a=>a!==null).length;
  const pct = ((currentQuestion+1)/totalQuestions)*100;
  document.getElementById('progress-fill').style.width = pct+'%';
  document.getElementById('answered-count').textContent = `${answered} of ${totalQuestions} answered`;
  document.getElementById('q-of').textContent = `Question ${currentQuestion+1} / ${totalQuestions}`;
  
  // Update buttons
  document.getElementById('btn-prev').disabled = currentQuestion===0;
  document.getElementById('btn-next').textContent = currentQuestion===totalQuestions-1 ? 'Finish' : 'Next';
  
  buildDots();
  
  // Animation
  const card = document.getElementById('question-' + currentQuestion);
  card.style.animation='none'; 
  void card.offsetHeight; 
  card.style.animation='fadeUp 0.35s ease';
}

function go(dir) {
  const next = currentQuestion+dir;
  if (next<0) return;
  if (next>=totalQuestions) { trySubmit(); return; }
  currentQuestion=next; 
  renderQ();
}

function selectOption(btn, questionId, answerId) {
  // Remove selected class from siblings
  const questionContainer = btn.closest('.q-card');
  questionContainer.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
  
  // Add selected class to clicked button
  btn.classList.add('selected');
  
  // Check the hidden radio
  const radio = btn.querySelector('input[type="radio"]');
  radio.checked = true;
  
  // Update answers array
  const questionIndex = Array.from(document.querySelectorAll('.q-card')).indexOf(questionContainer);
  answers[questionIndex] = answerId;
  
  updateAnsweredCount();
  buildDots();
}

function updateAnsweredCount() {
  let count = 0;
  for (let i = 0; i < totalQuestions; i++) {
    const answered = document.querySelector('#question-' + i + ' input[type="radio"]:checked');
    if (answered) count++;
  }
  document.getElementById('answered-count').innerText = count + ' of ' + totalQuestions + ' answered';
}

function trySubmit() {
  const unanswered = answers.filter(a=>a===null).length;
  if (unanswered>0) {
    showNotification(
      `You still have ${unanswered} unanswered question${unanswered>1?'s':''}. Please answer all questions before submitting.`,
      'warning',
      'Incomplete Test'
    );
    const first = answers.findIndex(a=>a===null);
    if (first!==-1) { 
      currentQuestion=first; 
      renderQ(); 
    }
    window.scrollTo({top:0,behavior:'smooth'});
    return;
  }
  if (confirm('Submit your test now? Your results will be revealed.')) {
    testForm.submit();
  }
}

function startTimer() {
  clearInterval(timerInt);
  timerInt=setInterval(()=>{
    timeLeft--;
    const m=Math.floor(timeLeft/60), s=timeLeft%60;
    document.getElementById('timer-display').textContent=`${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    if(timeLeft<=300) document.getElementById('timer-pill').classList.add('warning');
    if(timeLeft<=0){ 
      clearInterval(timerInt); 
      showNotification(
        'Time is up! Your test is being submitted automatically.',
        'error',
        'Time Expired'
      );
      setTimeout(() => testForm.submit(), 2000);
    }
  },1000);
}


// Professional notification system
function showNotification(message, type = 'info', title = '') {
  const container = document.getElementById('notification-container');
  const notification = document.createElement('div');
  notification.className = `notification ${type}`;
  
  const notificationHTML = `
    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
    ${title ? `<div class="notification-title">${title}</div>` : ''}
    <div class="notification-message">${message}</div>
  `;
  
  notification.innerHTML = notificationHTML;
  container.appendChild(notification);
  
  // Trigger animation
  setTimeout(() => notification.classList.add('show'), 10);
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => notification.remove(), 300);
  }, 5000);
}

function onTestSubmit() {
  const unanswered = answers.filter(a=>a===null).length;
  if (unanswered>0) {
    showNotification(
      `You still have ${unanswered} unanswered question${unanswered>1?'s':''}. Please answer all questions before submitting.`,
      'warning',
      'Incomplete Test'
    );
    return false;
  }
  return true;
}

// Prevent navigation away from test page
window.addEventListener('beforeunload', function(e) {
  const unanswered = answers.filter(a=>a===null).length;
  if (unanswered > 0 || timeLeft > 0) {
    e.preventDefault();
    e.returnValue = 'You have an ongoing test. Are you sure you want to leave? Your progress will be lost.';
    return e.returnValue;
  }
});

// Prevent page reload, dev tools, and other restricted actions
window.addEventListener('keydown', function(e) {
  const unanswered = answers.filter(a=>a===null).length;
  const testActive = unanswered > 0 || timeLeft > 0;
  
  if (!testActive) return;
  
  // Prevent F5 or Ctrl+R or Cmd+R (reload)
  if (e.key === 'F5' || (e.ctrlKey && e.key === 'r') || (e.metaKey && e.key === 'r')) {
    e.preventDefault();
    showNotification(
      'Page reload is not allowed during an active test. Please complete or submit your test first.',
      'error',
      'Reload Blocked'
    );
    return false;
  }
  
  // Prevent Ctrl+W or Cmd+W (close tab)
  if ((e.ctrlKey || e.metaKey) && e.key === 'w') {
    e.preventDefault();
    return false;
  }
  
  // Prevent dev tools (F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C)
  const devToolsKeys = ['F12', 'I', 'J', 'C'];
  const isDevTools = e.key === 'F12' || 
                    (e.ctrlKey && e.shiftKey && devToolsKeys.includes(e.key)) ||
                    (e.metaKey && e.altKey && e.key === 'I');
  
  if (isDevTools) {
    e.preventDefault();
    showNotification(
      'Developer tools are disabled during the test to maintain test integrity.',
      'error',
      'Action Restricted'
    );
    return false;
  }
  
  // Allow test navigation keys (arrow keys)
  if (e.key === 'ArrowRight') go(1);
  if (e.key === 'ArrowLeft') go(-1);
});

// Prevent context menu (right-click) during test
window.addEventListener('contextmenu', function(e) {
  const unanswered = answers.filter(a=>a===null).length;
  if (unanswered > 0 || timeLeft > 0) {
    e.preventDefault();
    showNotification(
      'Right-click is disabled during the test to maintain test integrity.',
      'info',
      'Action Restricted'
    );
    return false;
  }
});

// Prevent back button navigation
window.addEventListener('popstate', function(e) {
  const unanswered = answers.filter(a=>a===null).length;
  if (unanswered > 0 || timeLeft > 0) {
    e.preventDefault();
    window.history.pushState(null, null, window.location.href);
    return false;
  }
});

// Disable all navigation links during test
function disableNavigation() {
  const links = document.querySelectorAll('a:not([href*="test/submit"])');
  links.forEach(link => {
    if (link.href && !link.href.includes('test/submit')) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        showNotification(
          'You cannot navigate away during an active test. Please complete or submit your test first.',
          'error',
          'Navigation Restricted'
        );
        return false;
      });
    }
  });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
  init();
  disableNavigation();
  
  // Push initial state to prevent back navigation
  window.history.pushState(null, null, window.location.href);
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
