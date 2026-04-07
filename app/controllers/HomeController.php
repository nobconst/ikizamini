<?php

require_once '../core/Controller.php';

class HomeController extends Controller {
    
    public function index() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        // Get stats
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM users");
        $total_users = $stmt->fetch()['count'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM test_sessions WHERE completed = TRUE");
        $total_tests = $stmt->fetch()['count'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM payment_plans WHERE is_active = TRUE");
        $plans = $this->db->query("SELECT * FROM payment_plans WHERE is_active = TRUE")->fetchAll();
        
        $this->view('home', [
            'total_users' => $total_users,
            'total_tests' => $total_tests,
            'plans' => $plans
        ]);
    }

    public function about() {
        $this->view('about');
    }

    public function pricing() {
        $plans = $this->db->query("SELECT * FROM payment_plans WHERE is_active = TRUE")->fetchAll();
        $this->view('pricing', ['plans' => $plans]);
    }
}
