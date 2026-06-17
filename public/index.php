<?php
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path && is_file($path)) {
        return false;
    }
}
session_start();

// Define paths
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');

// Autoloader for core classes
spl_autoload_register(function ($class) {
    if (file_exists(APP . '/core/' . $class . '.php')) {
        require_once APP . '/core/' . $class . '.php';
    } elseif (file_exists(APP . '/controllers/' . $class . '.php')) {
        require_once APP . '/controllers/' . $class . '.php';
    } elseif (file_exists(APP . '/models/' . $class . '.php')) {
        require_once APP . '/models/' . $class . '.php';
    }
});

// Require routing definitions
require_once ROOT . '/routes/web.php';

// Initialize the Router
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = trim($url, '/');
Router::dispatch($url);
