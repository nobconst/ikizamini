<?php

require_once '../core/Controller.php';

class PawapayController extends Controller {

    public function processPayment() {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone    = $_POST['phone'] ?? '';
            $amount   = $_POST['amount'] ?? '';
            $currency = $_POST['currency'] ?? 'RWF';

            $depositId = uniqid('IKZ_');

            // Save pending payment
            $stmt = $this->db->prepare("
                INSERT INTO general_payments
                    (transaction_id, type, status, amount, currency, raw_data)
                VALUES
                    (?, 'deposit', 'PENDING', ?, ?, ?)
            ");
            $stmt->execute([$depositId, $amount, $currency, json_encode($_POST)]);

            // Send request to PawaPay
            $payload = [
                'depositId'            => $depositId,
                'amount'               => $amount,
                'currency'             => $currency,
                'payer'                => ['type' => 'MSISDN', 'address' => ['value' => $phone]],
                'country'              => 'RWA',
                'correspondent'        => 'MTN_MOMO_RWA',
                'customerTimestamp'    => date('c'),
                'statementDescription' => 'Ikizamini Payment'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.pawapay.io/v2/deposits');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer YOUR_LIVE_TOKEN',
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            // Save API response
            $update = $this->db->prepare("
                UPDATE general_payments SET raw_data = ? WHERE transaction_id = ?
            ");
            $update->execute([$response, $depositId]);

            $message = 'Payment request sent. Waiting for callback.';
        }

        $this->view('process_payment', ['message' => $message]);
    }

    private function handleCallback($type, $logFile) {
        http_response_code(200);

        $raw = file_get_contents('php://input');

        file_put_contents(
            __DIR__ . '/../../logs/pawapay_' . $type . '_logs.txt',
            '[' . date('Y-m-d H:i:s') . '] ' . $raw . PHP_EOL,
            FILE_APPEND
        );

        $data = json_decode($raw, true);

        if (!is_array($data)) {
            echo 'OK';
            exit;
        }

        if ($type === 'refund') {
            $refundId = $data['refundId']
                ?? $data['depositId']
                ?? $data['transactionId']
                ?? null;

            $status = $data['status'] ?? 'UNKNOWN';

            if ($refundId) {
                $stmt = $this->db->prepare("
                    INSERT INTO general_refunds (refund_id, status, raw_data)
                    VALUES (:refund_id, :status, :raw_data)
                    ON DUPLICATE KEY UPDATE status=VALUES(status), raw_data=VALUES(raw_data)
                ");
                $stmt->execute([
                    ':refund_id' => $refundId,
                    ':status'    => $status,
                    ':raw_data'  => $raw
                ]);
            }
        } else {
            $transactionId = $data['checkoutId']
                ?? $data['depositId']
                ?? $data['transactionId']
                ?? $data['id']
                ?? null;

            $status   = $data['status'] ?? 'UNKNOWN';
            $amount   = $data['requestedAmount'] ?? $data['amount'] ?? 0;
            $currency = $data['currency'] ?? '';

            if ($transactionId) {
                $stmt = $this->db->prepare("
                    INSERT INTO general_payments
                        (transaction_id, type, status, amount, currency, raw_data)
                    VALUES
                        (:transaction_id, :type, :status, :amount, :currency, :raw_data)
                    ON DUPLICATE KEY UPDATE
                        status=VALUES(status), amount=VALUES(amount),
                        currency=VALUES(currency), raw_data=VALUES(raw_data)
                ");
                $stmt->execute([
                    ':transaction_id' => $transactionId,
                    ':type'           => $type,
                    ':status'         => $status,
                    ':amount'         => $amount,
                    ':currency'       => $currency,
                    ':raw_data'       => $raw
                ]);
            }
        }

        echo 'OK';
        exit;
    }

    public function checkoutCallback() {
        $this->handleCallback('checkout', 'checkout');
    }

    public function depositCallback() {
        $this->handleCallback('deposit', 'deposit');
    }

    public function refundCallback() {
        $this->handleCallback('refund', 'refund');
    }
}
