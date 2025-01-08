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

class Router
{
    protected $routes = [];




    /**
     * Load error page
     * @param int $httpCode
     * @return void
     * 
     */
    public function registerRoute($method, $uri, $action)
    {
        list($controller, $controllerMethod) = explode('@', $action);
        // inspectAndDie($controllerMethod);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * Add GET route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {

        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add POST route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {

        $this->registerRoute('POST', $uri, $controller);
    }
    /**
     * Add PUT route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add DELETE route
     * @param string uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->registerRoute('DELETE', $uri, $controller);
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
                        $params[$matches[$i]] = $uriSegments[$i];
                        // inspectAndDie($params);
                    }
                }
                if ($match) {
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
