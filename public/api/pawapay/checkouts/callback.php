<?php
// api/pawapay/checkouts/callback.php

require_once '../config.php';

http_response_code(200);

$raw = file_get_contents("php://input");

file_put_contents(
    __DIR__ . "/checkout_logs.txt",
    "[" . date("Y-m-d H:i:s") . "] " . $raw . PHP_EOL,
    FILE_APPEND
);

$data = json_decode($raw, true);

if (!is_array($data)) {
    exit("OK");
}

$transactionId = $data['checkoutId']
    ?? $data['depositId']
    ?? $data['transactionId']
    ?? $data['id']
    ?? null;

$status = $data['status'] ?? 'UNKNOWN';

$amount = $data['requestedAmount']
    ?? $data['amount']
    ?? 0;

$currency = $data['currency'] ?? '';

if ($transactionId) {

    $stmt = $pdo->prepare("
        INSERT INTO general_payments
        (
            transaction_id,
            type,
            status,
            amount,
            currency,
            raw_data
        )
        VALUES
        (
            :transaction_id,
            'checkout',
            :status,
            :amount,
            :currency,
            :raw_data
        )

        ON DUPLICATE KEY UPDATE

            status=VALUES(status),
            amount=VALUES(amount),
            currency=VALUES(currency),
            raw_data=VALUES(raw_data)
    ");

    $stmt->execute([
        ':transaction_id'=>$transactionId,
        ':status'=>$status,
        ':amount'=>$amount,
        ':currency'=>$currency,
        ':raw_data'=>$raw
    ]);
}

echo "OK";