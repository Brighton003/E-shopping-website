<?php

class Router {
    private static $routes = [];

    public static function get($uri, $action) {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post($uri, $action) {
        self::$routes['POST'][$uri] = $action;
    }

    public static function dispatch($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Handle basic dynamic routing like /product/{id}
        foreach (self::$routes[$method] ?? [] as $route => $action) {
            // Replace {id} with regex pattern
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
            $pattern = "@^" . $pattern . "$@D";
            
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Remove full match
                return self::executeAction($action, $matches);
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }

    private static function executeAction($action, $params) {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_array($action)) {
            $controllerName = $action[0];
            $methodName = $action[1];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    return call_user_func_array([$controller, $methodName], $params);
                }
            }
        }

        throw new Exception("Route action not found.");
    }
}
