<?php

namespace App\Common;

class Router
{

    /**
     * All registered routes.
     *
     * @var array
     */
    public $routes = [
        'GET' => [],
        'POST' => []
    ];
    public $routeNames = [];

     /**
     * Load a user's routes file.
     *
     * @param string $file
     */
    public static function load($file)
    {
        $router = new static;
        
        include $file;

        return $router;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function get($uri, $controller)
    {
        $this->routeNames[] = $uri;
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function post($uri, $controller)
    {
        $this->routeNames[] = $uri;
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Load the requested URI's associated controller method.
     *
     * @param string $uri
     * @param string $requestType
     */
    public function direct($uri, $requestType)
    {
        if (!$uri) {
            throw new \Exception( esc_html('No route defined in this Reqeust.'));
        }
        $validRoutes = in_array($uri, $this->routeNames);

        if (array_key_exists($uri, $this->routes[$requestType]) && $validRoutes) {

            if(is_callable($this->routes[$requestType][$uri])) {
                return call_user_func($this->routes[$requestType][$uri]);
            }

            $action = explode('@', $this->routes[$requestType][$uri]);

            return $this->callAction(...$action);
        }
        throw new \Exception(esc_html('No route defined for this Routes : '  . $uri));
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param string $controller
     * @param string $action
     */
    protected function callAction($controller, $action)
    {

        $controller = "App\\Controllers\\{$controller}";
        $controller = new $controller;

        if (! method_exists($controller, $action)) {
            throw new \Exception(
                esc_html("{$controller} does not respond to the {$action} action.")
            );
        }
        return $controller->$action();
    }
}
