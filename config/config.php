<?php

// DATABASE CONFIGURATION
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'provisor_exam');

// SITE SETTINGS
define('SITE_NAME', 'IKIZAMINI ONLINE');
define('SITE_URL', 'http://localhost/ikizamini');
define('TIMEZONE', 'Africa/Kigali');

// SESSION
define('SESSION_TIMEOUT', 3600);

// TEST SETTINGS
define('TEST_DURATION', 1200); // 20 minutes
define('TOTAL_QUESTIONS', 20);
define('PASS_SCORE', 16); // 80% = 16/20

// PAYMENT SETTINGS
define('MOBILE_MONEY_API', 'https://api.example.com');
define('PAYMENT_TIMEOUT', 300);

// SECURITY
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_OPTIONS', ['cost' => 12]);

// Set timezone
date_default_timezone_set(TIMEZONE);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize language (default: Kinyarwanda)
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'rw';
}

// Include translation helper
$translate_path = __DIR__ . '/../core/Translate.php';
if (file_exists($translate_path)) {
    require_once $translate_path;
    Translate::setLanguage($_SESSION['lang']);
}
