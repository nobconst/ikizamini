<?php
ob_start();
?>

<div class="container">
    <div style="max-width: 600px; margin: 50px auto;">
        <div class="card">
            <div class="card-header">⏳ Processing Payment...</div>
            
            <div style="text-align: center; padding: 30px;">
                <div style="font-size: 48px; margin-bottom: 20px;">💳</div>
                <p>Your payment is being processed. Please wait...</p>
                <div style="background: linear-gradient(90deg, #ddd 0%, #ddd 50%, transparent 50%, transparent 100%);
                            background-size: 200% 100%;
                            animation: loading 1.5s infinite;
                            height: 4px;
                            border-radius: 2px;
                            margin: 30px 0;">
                </div>
                <p style="color: #666; font-size: 14px;">Do not close this page</p>
            </div>

            <div style="background: #f0f7ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px;">
                <p><strong>📱 Next Steps:</strong></p>
                <p>1. You will receive a prompt on your phone</p>
                <p>2. Enter your PIN to confirm payment</p>
                <p>3. Your tests will activate instantly</p>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<script>
// Check payment status every 3 seconds
let checkCount = 0;
const maxChecks = 20; // 60 seconds

function checkPaymentStatus() {
    let paymentId = '<?= $payment_id ?>';
    
    fetch('<?= SITE_URL ?>/payment/verify/' + paymentId, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = '<?= SITE_URL ?>/dashboard';
        } else if (data.status === 'failed') {
            window.location.href = '<?= SITE_URL ?>/payment';
        } else {
            checkCount++;
            if (checkCount < maxChecks) {
                setTimeout(checkPaymentStatus, 3000);
            } else {
                alert('Payment verification timeout. Please check your payment status.');
                window.location.href = '<?= SITE_URL ?>/payment/history';
            }
        }
    })
    .catch(err => {
        checkCount++;
        if (checkCount < maxChecks) {
            setTimeout(checkPaymentStatus, 3000);
        }
    });
}

// Start checking after 2 seconds
setTimeout(checkPaymentStatus, 2000);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
