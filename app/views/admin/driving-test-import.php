<?php
ob_start();
?>

<!-- XLSX Library for Excel processing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    .import-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 20px;
    }
    
    .card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }
    
    .file-input {
        width: 100%;
        padding: 12px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .file-input:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }
    
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        margin: 5px;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: #667eea;
        color: white;
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    
    .btn-warning {
        background: #ffc107;
        color: #212529;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .format-info {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }
    
    .format-info h4 {
        color: #495057;
        margin-bottom: 15px;
    }
    
    .format-info table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    
    .format-info th,
    .format-info td {
        border: 1px solid #dee2e6;
        padding: 8px 12px;
        text-align: left;
    }
    
    .format-info th {
        background: #e9ecef;
        font-weight: bold;
    }
    
    .preview-section {
        display: none;
        margin-top: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .preview-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }
    
    .preview-table th,
    .preview-table td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .preview-table th {
        background: #e9ecef;
        font-weight: bold;
    }
    
    .progress-bar {
        width: 100%;
        height: 20px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin: 10px 0;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        width: 0%;
        transition: width 0.3s ease;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }
    
    .stat-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        text-align: center;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #667eea;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin: 10px 0;
    }
    
    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }
</style>

<div class="import-container">
    <div class="card">
        <div class="card-header">
            <div style="display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 30px; margin-right: 15px;"></span>
                Import Driving Test Questions from CSV
            </div>
        </div>

        <div style="padding: 30px;">
            <!-- File Upload Section -->
            <div class="form-group">
                <label for="csv_file"> Select CSV File</label>
                <input type="file" id="csv_file" accept=".csv" class="file-input" onchange="handleFileSelect(this)">
                <small style="color: #6c757d; display: block; margin-top: 8px;">
                    Supported format: CSV (Maximum file size: 10MB)<br>
                    Your CSV should contain the driving test questions with the exact format from your driving_test.csv file
                </small>
            </div>

            <!-- CSV Format Information -->
            <div class="format-info">
                <h4>Expected CSV Format:</h4>
                <p>Your CSV file should have the following columns in this exact order:</p>
                <table>
                    <thead>
                        <tr>
                            <th>Column</th>
                            <th>Description</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>question_number</strong></td>
                            <td>Sequential question number (1-433)</td>
                            <td>1, 2, 3...</td>
                        </tr>
                        <tr>
                            <td><strong>question</strong></td>
                            <td>Question text in Kinyarwanda</td>
                            <td>"Ikinyabiziga cyose cyangwa..."</td>
                        </tr>
                        <tr>
                            <td><strong>choice1</strong></td>
                            <td>First answer option</td>
                            <td>"Umuyobozi"</td>
                        </tr>
                        <tr>
                            <td><strong>choice2</strong></td>
                            <td>Second answer option</td>
                            <td>"Umuherekeza"</td>
                        </tr>
                        <tr>
                            <td><strong>choice3</strong></td>
                            <td>Third answer option</td>
                            <td>"A na B ni ibisubizo by'ukuri"</td>
                        </tr>
                        <tr>
                            <td><strong>choice4</strong></td>
                            <td>Fourth answer option</td>
                            <td>"Nta gisubizo cy'ukuri kirimo"</td>
                        </tr>
                        <tr>
                            <td><strong>correct_answer</strong></td>
                            <td>Correct answer letter (a, b, c, d)</td>
                            <td>a, b, c, d</td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-radius: 8px;">
                    <h5 style="color: #1565c0; margin-top: 0;"> Quick Start:</h5>
                    <ol style="margin: 10px 0; padding-left: 20px; color: #424242;">
                        <li>Use your existing <code>driving_test.csv</code> file</li>
                        <li>Ensure it has exactly 433 questions</li>
                        <li>All questions should be in Kinyarwanda</li>
                        <li>Correct answers should be marked with letters a, b, c, or d</li>
                    </ol>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <button type="button" class="btn btn-info" onclick="downloadTemplate()">
                    Download CSV Template
                </button>
                <button type="button" class="btn btn-success" onclick="previewCSV()" id="previewBtn" disabled>
                    Preview CSV Data
                </button>
                <button type="button" class="btn btn-primary" onclick="importQuestions()" id="importBtn" disabled>
                    Import Questions to Database
                </button>
            </div>

            <!-- File Info -->
            <div id="fileInfo" style="display: none; margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <h5>Selected File:</h5>
                <div id="fileDetails"></div>
            </div>

            <!-- Preview Section -->
            <div id="previewSection" class="preview-section">
                <h4>CSV Data Preview (First 10 rows)</h4>
                <div id="previewContent"></div>
                
                <div class="stats-grid" id="statsGrid" style="display: none;">
                    <div class="stat-card">
                        <div class="stat-number" id="totalQuestions">0</div>
                        <div class="stat-label">Total Questions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="validQuestions">0</div>
                        <div class="stat-label">Valid Questions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="invalidQuestions">0</div>
                        <div class="stat-label">Invalid Questions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="duplicateQuestions">0</div>
                        <div class="stat-label">Duplicates</div>
                    </div>
                </div>
                
                <div id="validationErrors" style="margin-top: 15px;"></div>
            </div>

            <!-- Import Progress -->
            <div id="importProgress" style="display: none; margin: 20px 0;">
                <h5>Import Progress:</h5>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div id="progressText">0%</div>
            </div>

            <!-- Import Results -->
            <div id="importResults" style="display: none;"></div>
        </div>
    </div>
</div>

<script>
let csvData = [];
let validationResults = null;

function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) {
        document.getElementById('fileInfo').style.display = 'none';
        document.getElementById('previewBtn').disabled = true;
        document.getElementById('importBtn').disabled = true;
        return;
    }

    // Check file size (10MB limit)
    if (file.size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        input.value = '';
        return;
    }

    // Check file type
    if (!file.name.toLowerCase().endsWith('.csv')) {
        alert('Please select a CSV file');
        input.value = '';
        return;
    }

    // Show file info
    const fileInfo = document.getElementById('fileInfo');
    const fileDetails = document.getElementById('fileDetails');
    fileInfo.style.display = 'block';
    fileDetails.innerHTML = `
        <p><strong>Name:</strong> ${file.name}</p>
        <p><strong>Size:</strong> ${(file.size / 1024).toFixed(2)} KB</p>
        <p><strong>Type:</strong> ${file.type || 'CSV'}</p>
        <p><strong>Last Modified:</strong> ${new Date(file.lastModified).toLocaleString()}</p>
    `;

    // Enable buttons
    document.getElementById('previewBtn').disabled = false;
    document.getElementById('importBtn').disabled = true;

    // Reset previous data
    csvData = [];
    validationResults = null;
    document.getElementById('previewSection').style.display = 'none';
    document.getElementById('importResults').style.display = 'none';
}

function downloadTemplate() {
    const template = [
        ['question_number', 'question', 'choice1', 'choice2', 'choice3', 'choice4', 'correct_answer'],
        ['1', 'Ikinyabiziga cyose cyangwa ibinyabiziga bigenda bigomba kugira:', 'Umuyobozi', 'Umuherekeza', 'A na B ni ibisubizo by\'ukuri', 'Nta gisubizo cy\'ukuri kirimo', 'c'],
        ['2', 'Ijambo "akayira" bivuga inzira nyabagendwa ifunganye yagenewe gusa:', 'Abanyamaguru', 'Ibinyabiziga bigendera ku biziga bibiri', 'A na B ni ibisubizo by\'ukuri', 'Nta gisubizo cy\'ukuri kirimo', 'c'],
        ['3', 'Umurongo uciyemo uduce umenyesha ahegereye umurongo ushobora kuzuzwa n\'uturanga gukata tw\'ibara ryera utwo turanga cyerekezo tumenyesha:', 'Igisate cy\'umuhanda abayobozi bagomba gukurikira', 'Ahegereye umurongo ukomeje', 'Igabanurwa ry\'umubare w\'ibisate by\'umuhanda mu cyerekezo bajyamo', 'A na C nibyo', 'd']
    ];

    let csvContent = template.map(row => row.join(',')).join('\n');
    csvContent = '\ufeff' + csvContent; // Add BOM for UTF-8

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'driving_test_template.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function previewCSV() {
    const fileInput = document.getElementById('csv_file');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Please select a CSV file first');
        return;
    }

    const reader = new FileReader();
    
    reader.onload = function(e) {
        try {
            const text = e.target.result;
            csvData = parseCSV(text);
            
            if (csvData.length === 0) {
                alert('No data found in CSV file');
                return;
            }

            // Validate data
            validationResults = validateCSVData(csvData);
            
            // Show preview
            showPreview(csvData, validationResults);
            
            // Enable import button if there are valid questions
            if (validationResults.validQuestions.length > 0) {
                document.getElementById('importBtn').disabled = false;
            }
            
        } catch (error) {
            alert('Error reading CSV file: ' + error.message);
        }
    };
    
    reader.onerror = function() {
        alert('Error reading file');
    };
    
    reader.readAsText(file, 'UTF-8');
}

function parseCSV(text) {
    const lines = text.split('\n').filter(line => line.trim() !== '');
    const result = [];
    
    for (let i = 0; i < lines.length; i++) {
        const line = lines[i].trim();
        if (!line) continue;
        
        // Parse CSV handling quoted fields
        const fields = [];
        let current = '';
        let inQuotes = false;
        
        for (let j = 0; j < line.length; j++) {
            const char = line[j];
            
            if (char === '"') {
                if (inQuotes && j + 1 < line.length && line[j + 1] === '"') {
                    current += '"';
                    j++; // Skip next quote
                } else {
                    inQuotes = !inQuotes;
                }
            } else if (char === ',' && !inQuotes) {
                fields.push(current.trim());
                current = '';
            } else {
                current += char;
            }
        }
        fields.push(current.trim());
        
        if (fields.length >= 7) {
            result.push(fields);
        }
    }
    
    return result;
}

function validateCSVData(data) {
    const validQuestions = [];
    const invalidQuestions = [];
    const duplicateQuestions = [];
    const errors = [];
    const seenQuestions = new Set();
    
    // Skip header row if present
    const startIndex = data[0][0] === 'question_number' ? 1 : 0;
    
    for (let i = startIndex; i < data.length; i++) {
        const row = data[i];
        
        // Check row length
        if (row.length < 7) {
            invalidQuestions.push({ row: i + 1, data: row, error: 'Missing required columns' });
            errors.push(`Row ${i + 1}: Missing required columns`);
            continue;
        }
        
        const [question_number, question, choice1, choice2, choice3, choice4, correct_answer] = row;
        
        // Validate required fields
        if (!question || !choice1 || !choice2 || !choice3 || !choice4 || !correct_answer) {
            invalidQuestions.push({ row: i + 1, data: row, error: 'Missing required fields' });
            errors.push(`Row ${i + 1}: Missing required fields`);
            continue;
        }
        
        // Validate question number
        const qNum = parseInt(question_number);
        if (isNaN(qNum) || qNum < 1 || qNum > 500) {
            invalidQuestions.push({ row: i + 1, data: row, error: 'Invalid question number' });
            errors.push(`Row ${i + 1}: Invalid question number: ${question_number}`);
            continue;
        }
        
        // Validate correct answer
        if (!['a', 'b', 'c', 'd'].includes(correct_answer.toLowerCase())) {
            invalidQuestions.push({ row: i + 1, data: row, error: 'Invalid correct answer' });
            errors.push(`Row ${i + 1}: Invalid correct answer: ${correct_answer}`);
            continue;
        }
        
        // Check for duplicates
        const questionKey = question.toLowerCase().trim();
        if (seenQuestions.has(questionKey)) {
            duplicateQuestions.push({ row: i + 1, data: row, error: 'Duplicate question' });
            errors.push(`Row ${i + 1}: Duplicate question`);
        } else {
            seenQuestions.add(questionKey);
            validQuestions.push({
                row: i + 1,
                question_number: qNum,
                question: question.trim(),
                choice1: choice1.trim(),
                choice2: choice2.trim(),
                choice3: choice3.trim(),
                choice4: choice4.trim(),
                correct_answer: correct_answer.toLowerCase()
            });
        }
    }
    
    return {
        validQuestions,
        invalidQuestions,
        duplicateQuestions,
        errors,
        total: data.length - (data[0][0] === 'question_number' ? 1 : 0)
    };
}

function showPreview(data, results) {
    const previewSection = document.getElementById('previewSection');
    const previewContent = document.getElementById('previewContent');
    
    previewSection.style.display = 'block';
    
    // Create preview table (first 10 valid questions)
    const previewData = results.validQuestions.slice(0, 10);
    
    let tableHTML = `
        <div class="preview-table-wrapper" style="overflow-x: auto;">
            <table class="preview-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Choice 1</th>
                        <th>Choice 2</th>
                        <th>Choice 3</th>
                        <th>Choice 4</th>
                        <th>Correct</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    previewData.forEach((q, index) => {
        tableHTML += `
            <tr>
                <td>${q.question_number}</td>
                <td title="${q.question}">${q.question.substring(0, 50)}${q.question.length > 50 ? '...' : ''}</td>
                <td title="${q.choice1}">${q.choice1.substring(0, 30)}${q.choice1.length > 30 ? '...' : ''}</td>
                <td title="${q.choice2}">${q.choice2.substring(0, 30)}${q.choice2.length > 30 ? '...' : ''}</td>
                <td title="${q.choice3}">${q.choice3.substring(0, 30)}${q.choice3.length > 30 ? '...' : ''}</td>
                <td title="${q.choice4}">${q.choice4.substring(0, 30)}${q.choice4.length > 30 ? '...' : ''}</td>
                <td><strong>${q.correct_answer.toUpperCase()}</strong></td>
            </tr>
        `;
    });
    
    tableHTML += '</tbody></table></div>';
    previewContent.innerHTML = tableHTML;
    
    // Show statistics
    const statsGrid = document.getElementById('statsGrid');
    statsGrid.style.display = 'grid';
    
    document.getElementById('totalQuestions').textContent = results.total;
    document.getElementById('validQuestions').textContent = results.validQuestions.length;
    document.getElementById('invalidQuestions').textContent = results.invalidQuestions.length;
    document.getElementById('duplicateQuestions').textContent = results.duplicateQuestions.length;
    
    // Show validation errors if any
    const validationErrors = document.getElementById('validationErrors');
    if (results.errors.length > 0) {
        const errorHTML = `
            <div class="alert alert-warning">
                <h5>Validation Warnings (${results.errors.length}):</h5>
                <div style="max-height: 200px; overflow-y: auto;">
                    ${results.errors.slice(0, 20).map(error => `<div>${error}</div>`).join('')}
                    ${results.errors.length > 20 ? `<div>... and ${results.errors.length - 20} more</div>` : ''}
                </div>
            </div>
        `;
        validationErrors.innerHTML = errorHTML;
    } else {
        validationErrors.innerHTML = '<div class="alert alert-success">All questions passed validation!</div>';
    }
}

function importQuestions() {
    if (!validationResults || validationResults.validQuestions.length === 0) {
        alert('No valid questions to import');
        return;
    }
    
    const confirmed = confirm(
        `Ready to import ${validationResults.validQuestions.length} valid questions.\n\n` +
        `This will:\n` +
        `1. Add questions to the database\n` +
        `2. Create question translations in Kinyarwanda\n` +
        `3. Add answer options and mark correct answers\n\n` +
        `Continue with import?`
    );
    
    if (!confirmed) return;
    
    // Show progress
    document.getElementById('importProgress').style.display = 'block';
    document.getElementById('importResults').style.display = 'none';
    
    // Import questions
    importQuestionsToServer(validationResults.validQuestions);
}

function importQuestionsToServer(questions) {
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    
    // Simulate progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressFill.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '%';
    }, 200);
    
    // Create form data
    const formData = new FormData();
    formData.append('questions', JSON.stringify(questions));
    formData.append('category_id', '1'); // Default to Driving Test category
    
    // Send to server
    fetch('<?= SITE_URL ?>/admin/importDrivingTestQuestions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        progressFill.style.width = '100%';
        progressText.textContent = '100%';
        
        setTimeout(() => {
            document.getElementById('importProgress').style.display = 'none';
            showImportResults(data);
        }, 500);
    })
    .catch(error => {
        clearInterval(progressInterval);
        document.getElementById('importProgress').style.display = 'none';
        
        const resultsDiv = document.getElementById('importResults');
        resultsDiv.style.display = 'block';
        resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <h5>Import Failed</h5>
                <p>Error: ${error.message}</p>
            </div>
        `;
    });
}

function showImportResults(data) {
    const resultsDiv = document.getElementById('importResults');
    resultsDiv.style.display = 'block';
    
    if (data.success) {
        resultsDiv.innerHTML = `
            <div class="alert alert-success">
                <h5> Import Successful!</h5>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">${data.imported || 0}</div>
                        <div class="stat-label">Questions Imported</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${data.updated || 0}</div>
                        <div class="stat-label">Questions Updated</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${data.errors || 0}</div>
                        <div class="stat-label">Errors</div>
                    </div>
                </div>
                ${data.message ? `<p style="margin-top: 15px;">${data.message}</p>` : ''}
                <div style="margin-top: 20px;">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='<?= SITE_URL ?>/admin/questions'">
                        View All Questions
                    </button>
                    <button type="button" class="btn btn-info" onclick="location.reload()">
                        Import Another File
                    </button>
                </div>
            </div>
        `;
    } else {
        resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <h5> Import Failed</h5>
                <p>${data.message || 'Unknown error occurred'}</p>
                <div style="margin-top: 15px;">
                    <button type="button" class="btn btn-warning" onclick="location.reload()">
                        Try Again
                    </button>
                </div>
            </div>
        `;
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
