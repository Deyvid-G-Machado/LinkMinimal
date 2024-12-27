<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', function () {
        require __DIR__ . '/../src/Views/home.php';
    });

    // Route with parameter {id}
    $r->addRoute('GET', '/{id}', ['App\Controllers\LinkController', 'showId']);
    $r->addRoute('POST', '/link', ['App\Controllers\LinkController', 'create']);
});

// Get the HTTP method and URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Remove query string from the URI, if present
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Process the route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
    case FastRoute\Dispatcher::NOT_FOUND:
        require __DIR__ . '/../src/Views/notFound.php';
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1]; // Get the handler
        $vars = $routeInfo[2];    // Get the route variables, like {id}

        if (is_callable($handler)) {
            // If the handler is an anonymous function or callable
            call_user_func_array($handler, $vars);
        } elseif (is_array($handler) && count($handler) === 2) {
            // If the handler is an array [Class, Method]
            [$class, $method] = $handler;

            if (class_exists($class) && method_exists($class, $method)) {
                $instance = new $class();
                call_user_func_array([$instance, $method], $vars);
            } else {
                echo "Handler not found for {$class}::{$method}";
            }
        } else {
            echo "Invalid handler.";
        }
        break;
}
