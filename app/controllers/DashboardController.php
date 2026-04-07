<?php

require_once '../core/Controller.php';
require_once '../app/models/User.php';
require_once '../app/models\Payment.php';

class DashboardController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        
        // Get user info
        $user_model = new User();
        $user = $user_model->getById($user_id);
        
        // Check if user is admin or super_admin
        if ($user['role'] === 'admin' || $user['role'] === 'super_admin') {
            $this->redirect('/admin/dashboard');
        }
        
        // Get access info
        $stmt = $this->db->prepare("SELECT * FROM user_access WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $access = $stmt->fetch();
        
        // Get test history
        $stmt = $this->db->prepare("
            SELECT * FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$user_id]);
        $recent_tests = $stmt->fetchAll();
        
        $this->view('dashboard/index', [
            'user' => $user,
            'access' => $access,
            'recent_tests' => $recent_tests
        ]);
    }

    public function profile() {
        $user_id = $_SESSION['user_id'];
        $user_model = new User();
        $user = $user_model->getById($user_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            
            if (empty($name)) {
                $_SESSION['error'] = 'Name is required';
            } else {
                $user_model->update($user_id, ['name' => $name]);
                $_SESSION['user_name'] = $name;
                $_SESSION['success'] = 'Profile updated successfully';
            }
            
            header('Location: /dashboard/profile');
            exit;
        }
        
        $this->view('dashboard/profile', ['user' => $user]);
    }

    public function history() {
        $user_id = $_SESSION['user_id'];
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare("
            SELECT * FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$user_id, $limit, $offset]);
        $tests = $stmt->fetchAll();
        
        // Get total
        $total_stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
        ");
        $total_stmt->execute([$user_id]);
        $total = $total_stmt->fetch()['count'];
        $total_pages = ceil($total / $limit);
        
        $this->view('dashboard/history', [
            'tests' => $tests,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total' => $total
        ]);
    }
}
