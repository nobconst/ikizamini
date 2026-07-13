<?php
// api/pawapay/refunds/callback.php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../core/Database.php';

$pdo = (new Database())->connect();

http_response_code(200);

$raw = file_get_contents("php://input");

file_put_contents(
    __DIR__ . "/refund_logs.txt",
    "[" . date("Y-m-d H:i:s") . "] " . $raw . PHP_EOL,
    FILE_APPEND
);

$data = json_decode($raw, true);

if (!is_array($data)) {
    exit("OK");
}

$refundId = $data['refundId']
    ?? $data['depositId']
    ?? $data['transactionId']
    ?? null;

$status = $data['status'] ?? 'UNKNOWN';

if ($refundId) {

    $stmt = $pdo->prepare("
        INSERT INTO general_refunds
        (
            refund_id,
            status,
            raw_data
        )
        VALUES
        (
            :refund_id,
            :status,
            :raw_data
        )

        ON DUPLICATE KEY UPDATE

            status=VALUES(status),
            raw_data=VALUES(raw_data)
    ");

    $stmt->execute([
        ':refund_id'=>$refundId,
        ':status'=>$status,
        ':raw_data'=>$raw
    ]);
}

echo "OK";
