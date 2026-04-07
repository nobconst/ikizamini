<?php

require_once '../core/Controller.php';
require_once '../app/models/Payment.php';

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
            $_SESSION['error'] = 'Plan and phone are required';
            $this->redirect('/payment');
        }

        // Create payment record
        $payment_id = $this->payment->createPayment($user_id, $plan_id, $phone);

        if (!$payment_id) {
            $_SESSION['error'] = 'Failed to create payment';
            $this->redirect('/payment');
        }

        // Here you would integrate with Mobile Money API
        // For now, we'll simulate success after 5 seconds

        $this->log('PAYMENT_INITIATED', $user_id);

        $this->view('payment/processing', ['payment_id' => $payment_id]);
    }

    public function verify($payment_id) {
        // This would be called by a webhook or polling

        $payment = $this->payment->getPayment($payment_id);

        if (!$payment) {
            $this->json(['error' => 'Payment not found'], 404);
        }

        // In production, you would check with the Mobile Money provider
        // For demo, we simulate success

        if ($payment['status'] === 'pending') {
            $transaction_id = 'TXN-' . time();
            $this->payment->completePayment($payment_id, $transaction_id);
            $_SESSION['success'] = 'Payment successful!';
            $this->log('PAYMENT_SUCCESS', $payment['user_id']);
            $this->json(['status' => 'success']);
        } elseif ($payment['status'] === 'success') {
            $this->json(['status' => 'success']);
        } else {
            $this->json(['status' => 'failed']);
        }
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
