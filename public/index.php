<?php

require_once '../config/config.php';
require_once '../core/Router.php';

// Get URL
$url = $_GET['url'] ?? 'home';

// Create router and route
$router = new Router();
$router->route($url);
