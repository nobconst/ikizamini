<?php

require_once '../config/config.php';
require_once '../core/Database.php';

class Controller {
    
    protected $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function view($view, $data = []) {
        // Add current language to data
        $current_lang = $_SESSION['lang'] ?? 'en';
        $data['current_lang'] = $current_lang;
        
        extract($data);
        require_once '../app/views/' . $view . '.php';
    }

    public function redirect($url) {
        header('Location: ' . SITE_URL . $url);
        exit;
    }

    public function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'super_admin');
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }

    public function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }
    }

    public function log($action, $user_id = null) {
        $user_id = $user_id ?? ($_SESSION['user_id'] ?? null);
        
        // Check if user_id exists in users table
        if ($user_id !== null) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            if (!$stmt->fetch()) {
                $user_id = null; // User doesn't exist, set to null
            }
        }
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $this->db->prepare("
            INSERT INTO logs (user_id, action, ip_address, user_agent)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $action, $ip, $user_agent]);
    }
}
