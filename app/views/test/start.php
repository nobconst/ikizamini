<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 700px; margin: 50px auto;">
        <div class="card">
            <div class="card-header">🧪 Start Your Test</div>
            
            <div style="text-align: center;">
                <p style="font-size: 18px; margin-bottom: 20px;">Get ready for your driving exam!</p>
                
                <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: center;">
                        <div>
                            <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Questions</div>
                            <div style="font-size: 32px; font-weight: 700; color: #007bff;">20</div>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Duration</div>
                            <div style="font-size: 32px; font-weight: 700; color: #007bff;">20 min</div>
                        </div>
                    </div>
                </div>

                <div style="text-align: left; background: #f0f7ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px; margin-bottom: 30px;">
                    <h3 style="margin-top: 0;">📋 Test Rules:</h3>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>You must answer all 20 questions</li>
                        <li>Each question has 4 possible answers</li>
                        <li>You need 16/20 (80%) to pass</li>
                        <li>You cannot go back or pause the test</li>
                        <li>Cheating detection is enabled</li>
                    </ul>
                </div>

                <form method="POST" action="<?= SITE_URL ?>/test/start">
                    <button type="submit" class="btn btn-lg btn-success btn-block">Start Test Now 🚀</button>
                </form>

                <a href="<?= SITE_URL ?>/dashboard" class="btn btn-secondary btn-block" style="margin-top: 10px;">Cancel</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
