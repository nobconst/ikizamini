<?php

class Router {
    
    private $controller;
    private $method;
    private $params = [];

    public function route($url) {
        
        // Remove query strings
        $url = rtrim($url, '/');
        
        // Split URL into parts
        $parts = explode('/', filter_var($url, FILTER_SANITIZE_URL));
        
        // Get controller
        $this->controller = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
        
        // Get method (convert kebab-case to camelCase)
        $method = !empty($parts[1]) ? $parts[1] : 'index';
        $this->method = $this->kebabToCamel($method);
        
        // Get parameters
        $this->params = array_splice($parts, 2);
        
        // Check if file exists
        $controllerPath = '../app/controllers/' . $this->controller . '.php';

        if (!file_exists($controllerPath)) {
            // Support friendly URLs for static pages routed through HomeController
            $friendlyPages = ['about', 'pricing', 'contact'];
            if (in_array(strtolower($parts[0]), $friendlyPages)) {
                $this->controller = 'HomeController';
                $this->method = $this->kebabToCamel($parts[0]);
                $this->params = array_splice($parts, 1);
                $controllerPath = '../app/controllers/' . $this->controller . '.php';
                require_once $controllerPath;
            } else {
                $this->controller = 'ErrorController';
                $this->method = 'notfound';
                require_once '../app/controllers/ErrorController.php';
            }
        } else {
            require_once $controllerPath;
        }
        
        // Instantiate controller
        if (class_exists($this->controller)) {
            $controller = new $this->controller();
            
            if (method_exists($controller, $this->method)) {
                call_user_func_array([$controller, $this->method], $this->params);
            } else {
                echo "Method not found";
            }
        } else {
            echo "Controller not found";
        }
    }
    
    // Convert kebab-case to camelCase
    private function kebabToCamel($string) {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $string))));
    }
}
