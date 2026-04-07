<?php

require_once '../core/Database.php';

class Payment {
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getPlans() {
        $stmt = $this->db->query("
            SELECT * FROM payment_plans WHERE is_active = TRUE
            ORDER BY price ASC
        ");
        return $stmt->fetchAll();
    }

    public function getPlanById($id) {
        $stmt = $this->db->prepare("
            SELECT * FROM payment_plans WHERE id = ? AND is_active = TRUE
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createPayment($user_id, $plan_id, $phone) {
        $plan = $this->getPlanById($plan_id);
        
        if (!$plan) return false;
        
        $stmt = $this->db->prepare("
            INSERT INTO payments (user_id, plan_id, amount, phone, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        
        if ($stmt->execute([$user_id, $plan_id, $plan['price'], $phone])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getPayment($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, pp.name, pp.test_count, pp.duration_days
            FROM payments p
            JOIN payment_plans pp ON p.plan_id = pp.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function completePayment($payment_id, $transaction_id) {
        $payment = $this->getPayment($payment_id);
        
        if (!$payment) return false;
        
        // Update payment status
        $stmt = $this->db->prepare("
            UPDATE payments SET status = 'success', transaction_id = ?
            WHERE id = ?
        ");
        $stmt->execute([$transaction_id, $payment_id]);
        
        // Update user access
        if ($payment['test_count']) {
            // Test-based plan
            $access_stmt = $this->db->prepare("
                UPDATE user_access SET remaining_tests = remaining_tests + ?
                WHERE user_id = ?
            ");
            $access_stmt->execute([$payment['test_count'], $payment['user_id']]);
        } else {
            // Time-based plan
            $expires = date('Y-m-d H:i:s', strtotime("+" . $payment['duration_days'] . " days"));
            $access_stmt = $this->db->prepare("
                UPDATE user_access SET unlimited = TRUE, expires_at = ?
                WHERE user_id = ?
            ");
            $access_stmt->execute([$expires, $payment['user_id']]);
        }
        
        return true;
    }

    public function failPayment($payment_id, $reason = null) {
        $stmt = $this->db->prepare("
            UPDATE payments SET status = 'failed'
            WHERE id = ?
        ");
        return $stmt->execute([$payment_id]);
    }

    public function checkAccess($user_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM user_access WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $access = $stmt->fetch();
        
        if (!$access) return false;
        
        // Check if unlimited plan is active
        if ($access['unlimited']) {
            if ($access['expires_at'] && strtotime($access['expires_at']) > time()) {
                return true;
            } elseif (!$access['expires_at']) {
                return true;
            }
        }
        
        // Check if has remaining tests
        if ($access['remaining_tests'] > 0) {
            return true;
        }
        
        return false;
    }

    public function canTakeTest($user_id) {
        return $this->checkAccess($user_id);
    }

    public function consumeTest($user_id) {
        $stmt = $this->db->prepare("
            UPDATE user_access SET remaining_tests = remaining_tests - 1
            WHERE user_id = ? AND remaining_tests > 0
        ");
        return $stmt->execute([$user_id]);
    }

    public function getUserPaymentHistory($user_id, $limit = 50, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, pp.name as plan_name
            FROM payments p
            JOIN payment_plans pp ON p.plan_id = pp.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$user_id, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getSystemStats() {
        $stats = [];
        
        // Total revenue
        $stmt = $this->db->query("
            SELECT SUM(amount) as total FROM payments WHERE status = 'success'
        ");
        $stats['total_revenue'] = $stmt->fetch()['total'] ?? 0;
        
        // Total transactions
        $stmt = $this->db->query("
            SELECT COUNT(*) as count FROM payments
        ");
        $stats['total_payments'] = $stmt->fetch()['count'];
        
        // Successful payments
        $stmt = $this->db->query("
            SELECT COUNT(*) as count FROM payments WHERE status = 'success'
        ");
        $stats['successful_payments'] = $stmt->fetch()['count'];
        
        return $stats;
    }
}
