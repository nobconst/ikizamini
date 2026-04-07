<?php
ob_start();
?>

<?php
// Get user's current language
$user_lang = $_SESSION['lang'] ?? 'en';
?>

<style>
    .language-tabs {
        display: flex; 
        gap: 10px;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
    }
    
    .language-tab {
        padding: 12px 20px;
        background: #f0f0f0;
        border: 2px solid transparent;
        border-radius: 5px 5px 0 0;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s;
    }
    
    .language-tab:hover {
        background: #e0e0e0;
    }
    
    .language-tab.active {
        background: #007bff;
        color: white;
        border-bottom-color: #007bff;
    }
    
    .language-section {
        display: none;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 0 5px 5px 5px;
        background: #f9f9f9;
        margin-bottom: 20px;
    }
    
    .language-section.active {
        display: block;
    }
</style>

<div class="container">
    <div style="max-width: 700px; margin: 50px auto;">
        <div class="card">
            <div class="card-header">📖 View Question #<?= $question['id'] ?></div>

            <div style="padding: 20px;">
                <div class="form-group">
                    <label>📂 Category</label>
                    <p style="font-size: 16px; font-weight: bold;"><?= htmlspecialchars($question['category_name'] ?? 'N/A') ?></p>
                </div>

                <div class="form-group">
                    <label>📅 Created</label>
                    <p style="font-size: 16px;"><?= date('M d, Y H:i', strtotime($question['created_at'])) ?></p>
                </div>

                <!-- Question Image -->
                <?php if (!empty($question['image'])): ?>
                    <div class="form-group">
                        <label>📷 Question Image</label>
                        <div style="text-align: center; margin: 15px 0;">
                            <img src="<?= SITE_URL ?>/assets/images/questions/<?= htmlspecialchars($question['image']) ?>" 
                                 alt="Question Image" 
                                 style="max-width: 400px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; color: #666; font-style: italic;">Image not available</div>
                        </div>
                    </div>
                <?php endif; ?>

                <h3 style="margin-top: 30px; margin-bottom: 20px;">🌍 Question by Language</h3>
                
                <div class="language-tabs">
                    <div class="language-tab <?= $user_lang === 'en' ? 'active' : '' ?>" id="view-tab-en" onclick="showLanguageSection('en')">
                        🇬🇧 English
                    </div>
                    <?php if (!empty($question['question_fr'])): ?>
                        <div class="language-tab <?= $user_lang === 'fr' ? 'active' : '' ?>" id="view-tab-fr" onclick="showLanguageSection('fr')">
                            🇫🇷 Français
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($question['question_rw'])): ?>
                        <div class="language-tab <?= $user_lang === 'rw' ? 'active' : '' ?>" id="view-tab-rw" onclick="showLanguageSection('rw')">
                            🇷🇼 Kinyarwanda
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ENGLISH SECTION -->
                <div class="language-section <?= $user_lang === 'en' ? 'active' : '' ?>" id="view-section-en">
                    <h4 style="margin-top: 0; margin-bottom: 15px;">📝 Question (English)</h4>
                    <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin-bottom: 20px;">
                        <p style="margin: 0; font-size: 16px; line-height: 1.5;"><?= htmlspecialchars($question['question_text'] ?? 'N/A') ?></p>
                    </div>

                    <h5 style="margin-bottom: 15px;">Answers (English)</h5>
                    <?php foreach ($answers as $idx => $answer): ?>
                        <div style="background: #f0f0f0; padding: 15px; margin-bottom: 10px; border-radius: 5px; border-left: 4px solid <?= $answer['is_correct'] ? 'var(--success)' : '#ccc' ?>;">
                            <p style="margin: 0;">
                                <strong><?= chr(65 + $idx) ?>.</strong> <?= htmlspecialchars($answer['answer_text'] ?? 'N/A') ?>
                                <?php if ($answer['is_correct']): ?>
                                    <span style="background: var(--success); color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; margin-left: 10px;">✓ Correct</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- FRENCH SECTION -->
                <?php if (!empty($question['question_fr'])): ?>
                    <div class="language-section <?= $user_lang === 'fr' ? 'active' : '' ?>" id="view-section-fr">
                        <h4 style="margin-top: 0; margin-bottom: 15px;">📝 Question (Français)</h4>
                        <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin-bottom: 20px;">
                            <p style="margin: 0; font-size: 16px; line-height: 1.5;"><?= htmlspecialchars($question['question_fr'] ?? 'N/A') ?></p>
                        </div>

                        <h5 style="margin-bottom: 15px;">Answers (Français)</h5>
                        <?php foreach ($answers as $idx => $answer): ?>
                            <div style="background: #f0f0f0; padding: 15px; margin-bottom: 10px; border-radius: 5px; border-left: 4px solid <?= $answer['is_correct'] ? 'var(--success)' : '#ccc' ?>;">
                                <p style="margin: 0;">
                                    <strong><?= chr(65 + $idx) ?>.</strong> <?= htmlspecialchars($answer['answer_fr'] ?? 'N/A') ?>
                                    <?php if ($answer['is_correct']): ?>
                                        <span style="background: var(--success); color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; margin-left: 10px;">✓ Correct</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- KINYARWANDA SECTION -->
                <?php if (!empty($question['question_rw'])): ?>
                    <div class="language-section <?= $user_lang === 'rw' ? 'active' : '' ?>" id="view-section-rw">
                        <h4 style="margin-top: 0; margin-bottom: 15px;">📝 Question (Kinyarwanda)</h4>
                        <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; margin-bottom: 20px;">
                            <p style="margin: 0; font-size: 16px; line-height: 1.5;"><?= htmlspecialchars($question['question_rw'] ?? 'N/A') ?></p>
                        </div>

                        <h5 style="margin-bottom: 15px;">Answers (Kinyarwanda)</h5>
                        <?php foreach ($answers as $idx => $answer): ?>
                            <div style="background: #f0f0f0; padding: 15px; margin-bottom: 10px; border-radius: 5px; border-left: 4px solid <?= $answer['is_correct'] ? 'var(--success)' : '#ccc' ?>;">
                                <p style="margin: 0;">
                                    <strong><?= chr(65 + $idx) ?>.</strong> <?= htmlspecialchars($answer['answer_rw'] ?? 'N/A') ?>
                                    <?php if ($answer['is_correct']): ?>
                                        <span style="background: var(--success); color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; margin-left: 10px;">✓ Correct</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 30px; text-align: center;">
                    <a href="<?= SITE_URL ?>/admin/editQuestion/<?= $question['id'] ?>" class="btn btn-warning">✏️ Edit Question</a>
                    <a href="<?= SITE_URL ?>/admin/questions" class="btn btn-secondary">🔙 Back to Questions</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showLanguageSection(lang) {
    // Hide all sections
    document.querySelectorAll('.language-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.language-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById('view-section-' + lang).classList.add('active');
    document.getElementById('view-tab-' + lang).classList.add('active');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
