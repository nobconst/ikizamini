<?php

require_once '../core/Controller.php';
require_once '../app/models/User.php';

class AuthController extends Controller {
    
    private $user;

    public function __construct() {
        parent::__construct();
        $this->user = new User();
    }

    // Show login page
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }

    // Handle login
    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/login');
        }

        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($phone) || empty($password)) {
            $_SESSION['error'] = 'Phone and password are required';
            $this->redirect('/auth/login');
        }

        $user = $this->user->login($phone, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_role'] = $user['role'];
            
            $this->log('LOGIN_SUCCESS');

            if ($user['role'] === 'admin' || $user['role'] === 'super_admin') {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/dashboard');
            }
        } else {
            $_SESSION['error'] = 'Invalid phone or password';
            $this->log('LOGIN_FAILED');
            $this->redirect('/auth/login');
        }
    }

    // Show register page
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register');
    }

    // Handle register
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/register');
        }

        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($name) || empty($phone) || empty($password)) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect('/auth/register');
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('/auth/register');
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Passwords do not match';
            $this->redirect('/auth/register');
        }

        if ($this->user->getByPhone($phone)) {
            $_SESSION['error'] = 'Phone number already exists';
            $this->redirect('/auth/register');
        }

        $user_id = $this->user->register($name, $phone, $password);

        if ($user_id) {
            $_SESSION['success'] = 'Account created successfully. Please login.';
            $this->log('REGISTER_SUCCESS');
            $this->redirect('/auth/login');
        } else {
            $_SESSION['error'] = 'Registration failed. Try again.';
            $this->log('REGISTER_FAILED');
            $this->redirect('/auth/register');
        }
    }

    // Logout
    public function logout() {
        $this->log('LOGOUT');
        session_destroy();
        $this->redirect('/');
    }
}
