<?php
// Include the helpers file
require '../helpers.php';
require basePath('Framework/Router.php');
require basePath('Framework/Database.php');

// instaiiationg the router
$router = new Router();
// get routes
$routes = require basePath('routes.php');
// get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
// Route the request
$router->route($uri, $method);
