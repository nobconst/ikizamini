<?php ob_start(); ?>

<style>
/* ── screen styles ── */
.gt-wrap {
    max-width: 900px;
    margin: 0 auto;
    padding: 28px 20px 60px;
}
.gt-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 28px;
}
.gt-toolbar h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 900;
    color: #172033;
}
.gt-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    background: #fff;
    border: 1px solid #d9e2f2;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 28px;
    box-shadow: 0 4px 14px rgba(15,23,42,.05);
}
.gt-form label {
    font-size: 13px;
    font-weight: 800;
    color: #64748b;
    white-space: nowrap;
}
.gt-form select {
    height: 38px;
    padding: 0 10px;
    border: 1px solid #d9e2f2;
    border-radius: 6px;
    font-size: 14px;
    background: #f5f8fc;
    color: #172033;
}
.gt-form button {
    height: 38px;
    padding: 0 20px;
    border-radius: 6px;
    border: none;
    background: #0f6bbf;
    color: #fff;
    font-weight: 800;
    font-size: 14px;
    cursor: pointer;
}
.gt-form button:hover { background: #0a56a0; }

.gt-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.gt-btn-print {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 40px;
    padding: 0 20px;
    border-radius: 6px;
    border: 1px solid rgba(15,107,191,.28);
    background: #fff;
    color: #0f6bbf;
    font-weight: 800;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
}
.gt-btn-print:hover { background: #eef7ff; }

/* question cards */
.gt-list { display: grid; gap: 20px; }

.gt-card {
    background: #fff;
    border: 1px solid #d9e2f2;
    border-radius: 8px;
    padding: 20px 22px;
    box-shadow: 0 8px 20px rgba(15,23,42,.05);
    page-break-inside: avoid;
}
.gt-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.gt-qno {
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    color: #64748b;
}
.gt-qtext {
    font-size: 18px;
    font-weight: 800;
    color: #172033;
    line-height: 1.45;
    margin-bottom: 16px;
}
.gt-qimage {
    max-width: 100%;
    max-height: 220px;
    object-fit: contain;
    display: block;
    margin-bottom: 14px;
    border: 1px solid #d9e2f2;
    border-radius: 6px;
}
.gt-answers {
    display: grid;
    gap: 8px;
    margin-bottom: 14px;
}
.gt-answer {
    display: grid;
    grid-template-columns: 34px minmax(0,1fr);
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border: 1px solid #d9e2f2;
    border-radius: 6px;
    background: #f5f8fc;
    font-size: 15px;
    color: #172033;
}
.gt-letter {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid #d9e2f2;
    font-weight: 900;
    font-size: 13px;
    color: #64748b;
}
/* correct answer reveal strip */
.gt-correct-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 240px;
    padding: 10px 14px;
    background: #e9f8f5;
    border: 1px solid rgba(21,154,137,.35);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 800;
    color: #127a6d;
}
.gt-correct-strip .gt-correct-label {
    white-space: nowrap;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .04em;
    opacity: .75;
}
.gt-correct-strip .gt-correct-letter {
    width: 26px;
    height: 26px;
    border-radius: 5px;
    background: #159a89;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 13px;
    flex-shrink: 0;
}
.gt-correct-strip .gt-correct-text {
    color: #172033;
    font-weight: 800;
    font-size: 15px;
}
.gt-empty {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
    font-size: 16px;
    background: #fff;
    border: 1px dashed #d9e2f2;
    border-radius: 8px;
}

/* ── print styles ── */
@media print {
    body * { visibility: hidden; }
    #gt-printable, #gt-printable * { visibility: visible; }
    #gt-printable {
        position: absolute;
        inset: 0;
        padding: 20px;
    }
    .gt-toolbar,
    .gt-form,
    .gt-actions,
    .site-header,
    footer,
    .nav-overlay { display: none !important; }
    .gt-card {
        box-shadow: none;
        margin-bottom: 18px;
        page-break-inside: avoid;
    }
    .gt-correct-strip {
        background: #e9f8f5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .gt-correct-strip .gt-correct-letter {
        background: #159a89 !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .gt-print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 24px;
        border-bottom: 2px solid #172033;
        padding-bottom: 12px;
    }
}
.gt-print-header {
    display: none;
}
.gt-print-header-screen {
    display: none;
}
</style>

<div class="gt-wrap">
    <div class="gt-toolbar">
        <h1>📋 Generate Test Paper</h1>
        <a href="<?= SITE_URL ?>/admin/dashboard" class="btn btn-secondary btn-sm">← Admin Dashboard</a>
    </div>

    <!-- Filter form -->
    <form class="gt-form" method="GET" action="<?= SITE_URL ?>/admin/generateTest">
        <label>Language:</label>
        <select name="lang">
            <option value="rw" <?= $lang === 'rw' ? 'selected' : '' ?>>Kinyarwanda</option>
            <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
            <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>Français</option>
        </select>

        <label>Category:</label>
        <select name="category_id">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (string)$category_id === (string)$cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="generate" value="1">
        <button type="submit">🎲 Generate 20 Questions</button>
    </form>

    <?php if (!empty($questions)): ?>

        <div class="gt-actions">
            <button class="gt-btn-print" onclick="window.print()">🖨️ Print Test Paper</button>
            <a href="<?= SITE_URL ?>/admin/generateTest?lang=<?= urlencode($lang) ?>&category_id=<?= urlencode($category_id ?? '') ?>&generate=1"
               class="gt-btn-print">🔄 Regenerate</a>
        </div>

        <div id="gt-printable">
            <!-- Print header (hidden on screen) -->
            <div class="gt-print-header">
                <img src="<?= SITE_URL ?>/public/assets/images/ikizamini logo.png" alt="IKIZAMINI ONLINE" style="max-height:180px;object-fit:contain;margin-bottom:14px;">
                <h2 style="margin:0 0 6px;font-size:18px;font-weight:900;letter-spacing:.03em;">MENYA AMATEGEKO Y' UMUHANDA UTSINDIRE</h2>
                <h2 style="margin:0 0 6px;font-size:18px;font-weight:900;">POROVIZWARI (PROVISOUR)</h2>
                <h2 style="margin:0 0 16px;font-size:18px;font-weight:900;">MUGIHE GITO.</h2>
                <h3 style="margin:0;font-size:16px;font-weight:900;letter-spacing:.06em;">IBIBAZO N' IBISUBIZO</h3>
            </div>

            <div class="gt-list">
                <?php
                $letters = ['A','B','C','D'];
                foreach ($questions as $idx => $q):
                ?>
                <div class="gt-card">
                    <div class="gt-card-head">
                        <span class="gt-qno"><?= Translate::t('test_question_label') ?> <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?> / <?= count($questions) ?></span>
                    </div>

                    <?php if (!empty($q['image'])): ?>
                        <img src="<?= SITE_URL ?>/public/assets/images/questions/<?= htmlspecialchars($q['image']) ?>"
                             alt="Question image" class="gt-qimage"
                             onerror="this.style.display='none'">
                    <?php endif; ?>

                    <div class="gt-qtext"><?= htmlspecialchars($q['text']) ?></div>

                    <div class="gt-answers">
                        <?php foreach ($q['answers'] as $ai => $answer): ?>
                            <div class="gt-answer">
                                <span class="gt-letter"><?= $letters[$ai] ?? ($ai + 1) ?></span>
                                <span><?= htmlspecialchars($answer['text']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php
                        $correctIdx = null;
                        $correctText = '';
                        foreach ($q['answers'] as $ai => $answer) {
                            if ($answer['is_correct']) {
                                $correctIdx = $ai;
                                $correctText = $answer['text'];
                                break;
                            }
                        }
                    ?>
                    <?php if ($correctIdx !== null): ?>
                    <div class="gt-correct-strip">
                        <span class="gt-correct-label"><?= Translate::t('test_answer_label') ?>:</span>
                        <span class="gt-correct-letter"><?= $letters[$correctIdx] ?></span>
                        <span class="gt-correct-text"><?= htmlspecialchars($correctText) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php elseif (isset($_GET['generate'])): ?>
        <div class="gt-empty">
            ⚠️ No questions found for the selected language/category. Please add questions first.
        </div>
    <?php else: ?>
        <div class="gt-empty">
            Select a language and click <strong>Generate 20 Questions</strong> to create a test paper.
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
