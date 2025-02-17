<?php

// $routes = require basePath('routes.php');
// if (array_key_exists($uri, $routes)) {
//     require basePath($routes[$uri]);
// } else {
//     // Fallback to 404 page
//     http_response_code(404);
//     require basePath($routes['404']);
// }
namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;

class Router
{
    protected $routes = [];




    /**
     * Load error page
     * @param int $httpCode
     * @return void
     * 
     */
    public function registerRoute($method, $uri, $action, $middleware = [])
    {
        list($controller, $controllerMethod) = explode('@', $action);
        // inspectAndDie($controllerMethod);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware
        ];
    }

    /**
     * Add GET route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller, $middleware = [])
    {

        $this->registerRoute('GET', $uri, $controller, $middleware);
    }

    /**
     * Add POST route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller, $middleware = [])
    {

        $this->registerRoute('POST', $uri, $controller, $middleware = []);
    }
    /**
     * Add PUT route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller, $middleware = [])
    {
        $this->registerRoute('PUT', $uri, $controller, $middleware);
    }

    /**
     * Add DELETE route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller, $middleware = [])
    {
        $this->registerRoute('DELETE', $uri, $controller, $middleware);
    }
    /**
     * Route the request
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        // check for _method input 
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            /// override the request method with value of _method
            $requestMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            // Split the current URI into segments
            $uriSegments = explode('/', trim($uri, '/'));

            // Split the route URI into segments
            $routeSegments = explode('/', trim($route['uri'], '/'));
            $match = true;

            // Check if the number of segments matches and the request method matches
            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === $requestMethod) {
                $params = [];
                $match = true;
                for ($i = 0; $i < count($uriSegments); $i++) {
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match(
                        '/\{(.+?)\}/',
                        $routeSegments[$i]
                    )) {
                        $match = false;
                        break;
                    }
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i]; // <-- Correct
                    }
                }
                if ($match) {

                    foreach ($route['middleware'] as $middleware) {
                        (new Authorize())->handle($middleware);
                    }

                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // Instatiate the controller and call the method
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
        }
        // if ($route['uri'] === $uri && $route['method'] === $erquestMethod) {

        //     // Extract controller and controller method
        //     
        // }
        // If no route matches, show a 404 error
        ErrorController::unAuthorized();
    }
}
