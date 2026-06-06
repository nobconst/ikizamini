<?php

require_once '../core/Controller.php';
require_once '../app/models/Payment.php';
require_once '../app/models/pay_parse.php';

class PaymentController extends Controller {
    
    private $payment;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->payment = new Payment();
    }

    public function index() {
        $plans = $this->payment->getPlans();

        $this->view('payment/plans', ['plans' => $plans]);
    }

    public function checkout($plan_id) {
        $plan = $this->payment->getPlanById($plan_id);

        if (!$plan) {
            $_SESSION['error'] = 'Plan not found';
            $this->redirect('/payment');
        }

        $this->view('payment/checkout', ['plan' => $plan]);
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/payment');
        }

        $user_id = $_SESSION['user_id'];
        $plan_id = $_POST['plan_id'] ?? null;
        $phone = $_POST['phone'] ?? '';
        $payment_method = $_POST['payment_method'] ?? 'momo';

        if (!$plan_id || empty($phone)) {
            $_SESSION['error'] = Translate::t('payment_missing_plan_or_phone');
            $this->redirect('/payment');
        }

        $plan = $this->payment->getPlanById($plan_id);
        if (!$plan) {
            $_SESSION['error'] = Translate::t('payment_plan_not_found');
            $this->redirect('/payment');
        }

        $tx_ref = 'PAY-' . $user_id . '-' . time() . '-' . rand(100, 999);

        // Create payment record with gateway reference
        $payment_id = $this->payment->createPayment($user_id, $plan_id, $phone, $payment_method, $tx_ref);

        if (!$payment_id) {
            $_SESSION['error'] = Translate::t('payment_create_failed');
            $this->redirect('/payment');
        }

        $gatewayResult = hdev_payment::pay($phone, $plan['price'], $tx_ref, SITE_URL . '/payment/verify/' . $payment_id);

        if (!$gatewayResult || (isset($gatewayResult->error) && $gatewayResult->error)) {
            $this->payment->failPayment($payment_id);
            $_SESSION['error'] = Translate::t('payment_request_failed');
            $this->redirect('/payment');
        }

        if (isset($gatewayResult->status) && in_array(strtolower($gatewayResult->status), ['failed', 'error', 'declined'])) {
            $this->payment->failPayment($payment_id);
            $_SESSION['error'] = Translate::t('payment_request_failed');
            $this->redirect('/payment');
        }

        $this->log('PAYMENT_INITIATED', $user_id);
        $_SESSION['success'] = Translate::t('payment_prompt_sent');

        $this->view('payment/processing', ['payment_id' => $payment_id]);
    }

    public function verify($payment_id) {
        $payment = $this->payment->getPayment($payment_id);

        if (!$payment) {
            $this->json(['error' => Translate::t('payment_not_found')], 404);
        }

        if ($payment['user_id'] !== $_SESSION['user_id']) {
            $this->json(['error' => Translate::t('payment_unauthorized')], 403);
        }

        if ($payment['status'] === 'success') {
            $this->json(['status' => 'success']);
        }

        if ($payment['status'] === 'failed') {
            $this->json(['status' => 'failed']);
        }

        if (empty($payment['transaction_id'])) {
            $this->json(['status' => 'pending']);
        }

        $gatewayResult = hdev_payment::get_pay($payment['transaction_id']);
        $status = $this->parseHdevPaymentStatus($gatewayResult);

        if ($status === 'success') {
            $this->payment->completePayment($payment_id, $payment['transaction_id']);
            $_SESSION['success'] = Translate::t('payment_successful');
            $this->log('PAYMENT_SUCCESS', $payment['user_id']);
            $this->json(['status' => 'success']);
        }

        if ($status === 'failed') {
            $this->payment->failPayment($payment_id);
            $this->json(['status' => 'failed']);
        }

        $this->json(['status' => 'pending']);
    }

    private function parseHdevPaymentStatus($response) {
        if (!$response) {
            return 'pending';
        }

        if (isset($response->error) && $response->error) {
            return 'failed';
        }

        if (isset($response->status)) {
            $status = strtolower(trim($response->status));
            if (in_array($status, ['success', 'ok', 'completed', 'paid', 'successful'])) {
                return 'success';
            }
            if (in_array($status, ['failed', 'error', 'declined', 'cancelled', 'canceled'])) {
                return 'failed';
            }
            if (in_array($status, ['pending', 'processing', 'waiting', 'awaiting'])) {
                return 'pending';
            }
        }

        if (isset($response->data) && is_object($response->data) && isset($response->data->status)) {
            $status = strtolower(trim($response->data->status));
            if (in_array($status, ['success', 'ok', 'completed', 'paid', 'successful'])) {
                return 'success';
            }
            if (in_array($status, ['failed', 'error', 'declined', 'cancelled', 'canceled'])) {
                return 'failed';
            }
            if (in_array($status, ['pending', 'processing', 'waiting', 'awaiting'])) {
                return 'pending';
            }
        }

        if (isset($response->message) && stripos($response->message, 'success') !== false) {
            return 'success';
        }

        if (isset($response->message) && stripos($response->message, 'failed') !== false) {
            return 'failed';
        }

        return 'pending';
    }

    public function history() {
        $user_id = $_SESSION['user_id'];
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $history = $this->payment->getUserPaymentHistory($user_id, $limit, $offset);

        $this->view('payment/history', [
            'history' => $history,
            'page' => $page
        ]);
    }
}
