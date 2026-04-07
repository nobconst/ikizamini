<?php
ob_start();
?>

<?php
// Get user's current language
$user_lang = $_SESSION['lang'] ?? 'en';
?>

<!-- XLSX Library for Excel processing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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
            <div class="card-header">➕ Add New Question</div>

            <form method="POST" action="<?= SITE_URL ?>/admin/question/add" enctype="multipart/form-data" id="addQuestionForm">
                <div class="form-group">
                    <label for="category_id">📂 Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">📷 Question Image (Optional)</label>
                    <input type="file" id="image" name="image" accept="image/*" style="padding: 10px; display: block;">
                    <small style="color: #666; margin-top: 5px;">Accepted formats: JPG, PNG, GIF, WebP (Max 2MB)</small>
                </div>

                <h3 style="margin-top: 30px; margin-bottom: 20px;">🌍 Select Languages</h3>
                <p style="color: #666; margin-bottom: 15px;">Select which language(s) to add. For each language, all 4 answers must be filled and exactly ONE marked as correct.</p>
                
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
                        <textarea name="question_en" placeholder="Question text in English" rows="4"></textarea>
                    </div>

                    <h4 style="margin-top: 25px; margin-bottom: 15px;">Answers (English)</h4>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-bottom: 15px; background: white;">
                            <h5 style="margin-top: 0;">Option <?= chr(64 + $i) ?></h5>
                            <input type="text" name="answer_en_<?= $i ?>" placeholder="Answer option <?= $i ?> in English" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" name="correct_en" value="<?= $i ?>">
                                ✓ This is the correct answer
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- FRENCH SECTION -->
                <div class="language-section <?= $user_lang === 'fr' ? 'active' : '' ?>" id="section-fr">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">📝 Question (Français)</h3>
                    <div class="form-group">
                        <textarea name="question_fr" placeholder="Question text in French" rows="4"></textarea>
                    </div>

                    <h4 style="margin-top: 25px; margin-bottom: 15px;">Answers (Français)</h4>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-bottom: 15px; background: white;">
                            <h5 style="margin-top: 0;">Option <?= chr(64 + $i) ?></h5>
                            <input type="text" name="answer_fr_<?= $i ?>" placeholder="Answer option <?= $i ?> in French" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" name="correct_fr" value="<?= $i ?>">
                                ✓ This is the correct answer
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- KINYARWANDA SECTION -->
                <div class="language-section <?= $user_lang === 'rw' ? 'active' : '' ?>" id="section-rw">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">📝 Question (Kinyarwanda)</h3>
                    <div class="form-group">
                        <textarea name="question_rw" placeholder="Question text in Kinyarwanda" rows="4"></textarea>
                    </div>

                    <h4 style="margin-top: 25px; margin-bottom: 15px;">Answers (Kinyarwanda)</h4>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 5px; margin-bottom: 15px; background: white;">
                            <h5 style="margin-top: 0;">Option <?= chr(64 + $i) ?></h5>
                            <input type="text" name="answer_rw_<?= $i ?>" placeholder="Answer option <?= $i ?> in Kinyarwanda" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            <label>
                                <input type="checkbox" name="correct_rw" value="<?= $i ?>">
                                ✓ This is the correct answer
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block" onclick="return validateLanguages()">➕ Add Question</button>
            </form>
        </div>
    </div>
</div>

<!-- Excel Import Section -->
<div style="max-width: 700px; margin: 30px auto;">
    <div class="card">
        <div class="card-header">📊 Import Questions from Excel</div>
        
        <div style="padding: 20px;">
            <div class="form-group">
                <label for="excel_file">📁 Select Excel File</label>
                <input type="file" id="excel_file" accept=".xlsx,.xls" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <small style="color: #666; margin-top: 5px;">Supported formats: .xlsx, .xls (Maximum file size: 5MB)</small>
            </div>

            <div class="form-group">
                <label>📋 Expected Excel Format:</label>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #e9ecef; margin-bottom: 15px;">
                    <p style="margin: 0 0 10px 0; font-weight: bold;">Your Excel file should have the following columns:</p>
                    <ol style="margin: 0; padding-left: 20px;">
                        <li><strong>question_text</strong> - Question text</li>
                        <li><strong>category</strong> - Category name (Road Signs, Measurements, Rules)</li>
                        <li><strong>answer_1</strong> - First answer option</li>
                        <li><strong>answer_2</strong> - Second answer option</li>
                        <li><strong>answer_3</strong> - Third answer option</li>
                        <li><strong>answer_4</strong> - Fourth answer option</li>
                        <li><strong>correct_answer</strong> - Number of correct answer (1, 2, 3, or 4)</li>
                        <li><strong>language</strong> - Language code (en, fr, rw)</li>
                        <li><strong>image_url</strong> - Optional image URL (leave empty if no image)</li>
                    </ol>
                    <p style="margin: 10px 0 0 0; color: #666; font-size: 12px;">
                        💡 <strong>Tip:</strong> Create one row per question. You can include multiple questions in the same file.
                    </p>
                </div>

            <div style="margin-top: 20px;">
                <button type="button" class="btn btn-info" onclick="downloadTemplate()">📥 Download Excel Template</button>
                <button type="button" class="btn btn-success" onclick="importExcel()">📥 Import Excel File</button>
            </div>
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

function downloadTemplate() {
    // Create Excel template data
    const template = [
        ['question_text', 'category', 'answer_1', 'answer_2', 'answer_3', 'answer_4', 'correct_answer', 'language', 'image_url'],
        ['Sample question about traffic signs?', 'Road Signs', 'Stop completely', 'Yield to other traffic', 'Give way', 'Slow down', 'Stop', 'Proceed with caution', '1', 'en', ''],
        ['What does a red traffic light mean?', 'Measurements', '10 meters', '20 meters', '30 meters', '40 meters', '50 meters', '2', 'fr', ''],
        ['Sample Kinyarwanda question', 'Rules', 'Ibyo', 'Sibyo', 'Ikindi', 'Gatanya', '3', 'rw', 'https://example.com/image.jpg']
    ];

    // Create CSV content
    let csvContent = template.map(row => row.join(',')).join('\n');
    
    // Add BOM for proper UTF-8 encoding
    csvContent = '\ufeff' + csvContent;
    
    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'question_template.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function importExcel() {
    const fileInput = document.getElementById('excel_file');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Please select an Excel file to import');
        return;
    }
    
    // Check file size (5MB limit)
    if (file.size > 5 * 1024 * 1024) {
        alert('File size must be less than 5MB');
        return;
    }
    
    const reader = new FileReader();
    
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const jsonData = XLSX.utils.sheet_to_json(firstSheet, {header: 1});
            
            if (jsonData.length === 0) {
                alert('No questions found in the Excel file');
                return;
            }
            
            // Validate and process questions
            const errors = [];
            let processedCount = 0;
            
            jsonData.forEach((row, index) => {
                // Validate required fields
                if (!row.question_text || !row.category || !row.answer_1 || !row.answer_2 || !row.answer_3 || !row.answer_4 || !row.correct_answer || !row.language) {
                    errors.push(`Row ${index + 1}: Missing required fields`);
                    return;
                }
                
                // Validate category
                const validCategories = ['Road Signs', 'Measurements', 'Rules'];
                if (!validCategories.includes(row.category)) {
                    errors.push(`Row ${index + 1}: Invalid category "${row.category}". Valid categories: ${validCategories.join(', ')}`);
                    return;
                }
                
                // Validate correct answer
                if (![1, 2, 3, 4].includes(parseInt(row.correct_answer))) {
                    errors.push(`Row ${index + 1}: Invalid correct answer "${row.correct_answer}". Must be 1, 2, 3, or 4`);
                    return;
                }
                
                // Validate language
                const validLanguages = ['en', 'fr', 'rw'];
                if (!validLanguages.includes(row.language)) {
                    errors.push(`Row ${index + 1}: Invalid language "${row.language}". Valid languages: ${validLanguages.join(', ')}`);
                    return;
                }
                
                processedCount++;
            });
            
            if (errors.length > 0) {
                alert('Import validation errors:\n' + errors.join('\n'));
                return;
            }
            
            // Show preview and confirmation
            const preview = jsonData.slice(0, 3).map((row, i) => 
                `Row ${i + 1}: ${row.question_text?.substring(0, 50)}${row.question_text?.length > 50 ? '...' : ''} (${row.language})`
            ).join('\n');
            
            const confirmed = confirm(`Found ${processedCount} questions to import.\n\nPreview:\n${preview}\n\nContinue with import?`);
            
            if (confirmed) {
                // Send data to server via AJAX
                importQuestionsToServer(jsonData);
            }
            
        } catch (error) {
            alert('Error reading Excel file: ' + error.message);
        }
    };
    
    reader.onerror = function() {
        alert('Error reading file');
    };
    
    // Read the file
    reader.readAsArrayBuffer(file);
}

function importQuestionsToServer(questions) {
    // Create form data
    const formData = new FormData();
    formData.append('questions', JSON.stringify(questions));
    
    // Send to server
    fetch('<?= SITE_URL ?>/admin/importQuestions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Successfully imported ${data.imported} questions!${data.errors ? ' Some errors occurred: ' + data.errors : ''}`);
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            alert('Import failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Import error: ' + error.message);
    });
}

function validateLanguages() {
    const form = document.getElementById('addQuestionForm');
    const langCheckboxes = form.querySelectorAll('input[name="languages[]"]');
    const selectedLangs = Array.from(langCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
    
    if (selectedLangs.length === 0) {
        alert('Please select at least one language');
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
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
