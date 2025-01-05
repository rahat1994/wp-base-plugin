<?php

namespace App\Common;

class Router
{

    /**
     * The single instance of the class.
     *
     * @var Router
     */
    private static $instance = null;

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
     * Get the single instance of the class.
     *
     * @return Router
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
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
            throw new \Exception('No route defined in this Reqeust.');
        }
        $validRoutes = in_array($uri, $this->routeNames);

        if (array_key_exists($uri, $this->routes[$requestType]) && $validRoutes) {

            $action = explode('@', $this->routes[$requestType][$uri]);

            return $this->callAction(...$action);
        }
        throw new \Exception('No route defined for this Routes : '  . $uri);
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param string $controller
     * @param string $action
     */
    protected function callAction($controller, $action)
    {
        require DLCT_PLUGIN_DIR . '/app/Controllers/LogController.php';
        $controller = "DebugLogConfigTool\\Controllers\\{$controller}";
        $controller = new $controller;

        if (! method_exists($controller, $action)) {
            throw new \Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }
        return $controller->$action();
    }
}
