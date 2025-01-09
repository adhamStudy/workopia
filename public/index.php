<?php
// Include the helpers file
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Seassion;


Seassion::start();
require '../helpers.php';
// instaiiationg the router
$router = new Router();

// inspectAndDie(session_status());

// get routes
$routes = require basePath('routes.php');
// get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Route the request
$router->route($uri);
