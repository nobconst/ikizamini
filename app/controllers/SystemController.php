<?php

require_once '../core/Controller.php';

class SystemController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    // Set language preference
    public function setLanguage() {
        $language = $_POST['language'] ?? 'en';
        
        // Validate language
        if (!in_array($language, ['en', 'fr', 'rw'])) {
            $language = 'en';
        }
        
        // Store in session and Translate static state
        $_SESSION['lang'] = $language;
        Translate::setLanguage($language);
        
        // Redirect back to previous page or home
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }
}
