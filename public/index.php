<?php
// Include the helpers file
require '../helpers.php';
require basePath('Router.php');
require basePath('Database.php');

// instaiiationg the router
$router = new Router();
// get routes
$routes = require basePath('routes.php');
// get current URI and HTTP method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
// Route the request
$router->route($uri, $method);
