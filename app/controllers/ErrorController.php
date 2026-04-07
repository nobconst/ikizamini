<?php

class ErrorController {
    
    public function notfound() {
        http_response_code(404);
        require_once '../app/views/errors/404.php';
    }

    public function unauthorized() {
        http_response_code(403);
        require_once '../app/views/errors/403.php';
    }
}
