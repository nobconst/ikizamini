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
    
    .language-tab input[type="checkbox"] {
        margin-right: 8px;
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
            <div class="card-header">✏️ Edit Question #<?= $question['id'] ?></div>

            <form method="POST" enctype="multipart/form-data" id="editQuestionForm">
                <div class="form-group">
                    <label for="category_id">📂 Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $question['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Current Image Display -->
                <?php if (!empty($question['image'])): ?>
                    <div class="form-group">
                        <label>📷 Current Question Image</label>
                        <div style="background: #f0f8ff; padding: 15px; border-radius: 8px; border: 1px solid #b3d9ff;">
                            <img src="<?= SITE_URL ?>/assets/images/questions/<?= htmlspecialchars($question['image']) ?>" 
                                 alt="Current Question Image" 
                                 style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; color: #666; font-style: italic;">Current image not available</div>
                            <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                                Filename: <?= htmlspecialchars($question['image']) ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="image">📷 Upload New Image (Optional)</label>
                    <input type="file" id="image" name="image" accept="image/*" style="padding: 10px; display: block;">
                    <small style="color: #666; margin-top: 5px;">Leave empty to keep current image. Accepted formats: JPG, PNG, GIF, WebP (Max 2MB)</small>
                </div>

                <h3 style="margin-top: 30px; margin-bottom: 20px;">🌍 Edit Languages</h3>
                <p style="color: #666; margin-bottom: 15px;">Select which language(s) to edit. For each language, all 4 answers must be filled and exactly ONE marked as correct.</p>
                
                <div class="language-tabs">
                    <label class="language-tab <?= $user_lang === 'en' ? 'active' : '' ?>" id="tab-en">
                        <input type="checkbox" name="languages[]" value="en" <?= $user_lang === 'en' ? 'checked' : '' ?> onchange="toggleLanguageSection('en', this)">
                        🇬🇧 English
                    </label>
                    <label class="language-tab <?= $user_lang === 'fr' ? 'active' : '' ?>" id="tab-fr">
                        <input type="checkbox" name="languages[]" value="fr" <?= $user_lang === 'fr' ? 'checked' : '' ?> onchange="toggleLanguageSection('fr', this)">
                        🇫🇷 Français
                    </label>
                    <label class="language-tab <?= $user_lang === 'rw' ? 'active' : '' ?>" id="tab-rw">
                        <input type="checkbox" name="languages[]" value="rw" <?= $user_lang === 'rw' ? 'checked' : '' ?> onchange="toggleLanguageSection('rw', this)">
                        🇷🇼 Kinyarwanda
                    </label>
                </div>

                <!-- ENGLISH SECTION -->
                <div class="language-section <?= $user_lang === 'en' ? 'active' : '' ?>" id="section-en">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">📝 Question (English)</h3>
                    <div class="form-group">
                        <textarea name="question_en" placeholder="Question text in English" rows="4"><?= htmlspecialchars($question['question_en'] ?? '') ?></textarea>
                    </div>

                    <h4 style="margin-top: 25px; margin-bottom: 15px;">Answers (English)</h4>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <?php $answer = $answers[$i - 1] ?? null; ?>
                        <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-bottom: 15px; background: white;">
                            <h5 style="margin-top: 0;">Option <?= chr(64 + $i) ?></h5>
                            <input type="text" name="answer_en_<?= $i ?>" placeholder="Answer option <?= $i ?> in English" value="<?= htmlspecialchars($answer['answer_en'] ?? '') ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" name="correct_en" value="<?= $i ?>" <?= ($answer['is_correct'] ?? false) ? 'checked' : '' ?>>
                                ✓ This is the correct answer
                            </label>
                            <input type="hidden" name="answer_<?= $i ?>_id" value="<?= htmlspecialchars($answer['id'] ?? '') ?>">
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- FRENCH SECTION -->
                <div class="language-section <?= $user_lang === 'fr' ? 'active' : '' ?>" id="section-fr">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">📝 Question (Français)</h3>
                    <div class="form-group">
                        <textarea name="question_fr" placeholder="Question text in French" rows="4"><?= htmlspecialchars($question['question_fr'] ?? '') ?></textarea>
                    </div>

                    <h4 style="margin-top: 25px; margin-bottom: 15px;">Answers (Français)</h4>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <?php $answer = $answers[$i - 1] ?? null; ?>
                        <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-bottom: 15px; background: white;">
                            <h5 style="margin-top: 0;">Option <?= chr(64 + $i) ?></h5>
                            <input type="text" name="answer_fr_<?= $i ?>" placeholder="Answer option <?= $i ?> in French" value="<?= htmlspecialchars($answer['answer_fr'] ?? '') ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" name="correct_fr" value="<?= $i ?>" <?= ($answer['is_correct'] ?? false) ? 'checked' : '' ?>>
                                ✓ This is the correct answer
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- KINYARWANDA SECTION -->
                <div class="language-section <?= $user_lang === 'rw' ? 'active' : '' ?>" id="section-rw">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">📝 Question (Kinyarwanda)</h3>
                    <div class="form-group">
                        <textarea name="question_rw" placeholder="Question text in Kinyarwanda" rows="4"><?= htmlspecialchars($question['question_rw'] ?? '') ?></textarea>
                    </div>

                    <h4 style="margin-top: 25px; margin-bottom: 15px;">Answers (Kinyarwanda)</h4>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <?php $answer = $answers[$i - 1] ?? null; ?>
                        <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-bottom: 15px; background: white;">
                            <h5 style="margin-top: 0;">Option <?= chr(64 + $i) ?></h5>
                            <input type="text" name="answer_rw_<?= $i ?>" placeholder="Answer option <?= $i ?> in Kinyarwanda" value="<?= htmlspecialchars($answer['answer_rw'] ?? '') ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" name="correct_rw" value="<?= $i ?>" <?= ($answer['is_correct'] ?? false) ? 'checked' : '' ?>>
                                ✓ This is the correct answer
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-success" onclick="return validateLanguages()">💾 Save Changes</button>
                    <a href="<?= SITE_URL ?>/admin/questions" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleLanguageSection(lang, checkbox) {
    const tab = document.getElementById('tab-' + lang);
    const section = document.getElementById('section-' + lang);
    
    if (checkbox.checked) {
        tab.classList.add('active');
        section.classList.add('active');
    } else {
        tab.classList.remove('active');
        section.classList.remove('active');
    }
}

function validateLanguages() {
    const form = document.getElementById('editQuestionForm');
    const langCheckboxes = form.querySelectorAll('input[name="languages[]"]');
    const selectedLangs = Array.from(langCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
    
    if (selectedLangs.length === 0) {
        alert('Please select at least one language to edit');
        return false;
    }

    // Validate each selected language
    for (const lang of selectedLangs) {
        const questionField = form.querySelector(`textarea[name="question_${lang}"]`);
        if (!questionField || !questionField.value.trim()) {
            alert(`Question text is required for ${lang.toUpperCase()}`);
            return false;
        }

        // Check all 4 answers are filled
        for (let i = 1; i <= 4; i++) {
            const answerField = form.querySelector(`input[name="answer_${lang}_${i}"]`);
            if (!answerField || !answerField.value.trim()) {
                alert(`All 4 answer options must be filled for ${lang.toUpperCase()}`);
                return false;
            }
        }

        // Check exactly one correct answer
        const correctCheckboxes = form.querySelectorAll(`input[name="correct_${lang}"]:checked`);
        if (correctCheckboxes.length !== 1) {
            alert(`Please select exactly ONE correct answer for ${lang.toUpperCase()}`);
            return false;
        }
    }

    return true;
}

// Initialize language sections based on existing data
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editQuestionForm');
    
    // Check which languages have data and auto-select them
    const languages = ['en', 'fr', 'rw'];
    languages.forEach(lang => {
        const questionField = form.querySelector(`textarea[name="question_${lang}"]`);
        const hasData = questionField && questionField.value.trim();
        
        if (hasData) {
            const checkbox = form.querySelector(`input[name="languages[]"][value="${lang}"]`);
            const tab = document.getElementById('tab-' + lang);
            const section = document.getElementById('section-' + lang);
            
            checkbox.checked = true;
            tab.classList.add('active');
            section.classList.add('active');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
