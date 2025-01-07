<?php
// Include the helpers file
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;

spl_autoload_register(function ($class) {
    $path = basePath('Framework/' . $class . '.php');
    if (file_exists($path)) {
        require $path;
    }
});
// instaiiationg the router
$router = new Router();
// get routes
$routes = require basePath('routes.php');
// get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Route the request
$router->route($uri);
