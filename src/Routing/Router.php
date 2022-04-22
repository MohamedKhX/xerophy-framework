<?php

namespace Xerophy\Framework\Routing;

use Xerophy\Framework\Http\Request;

class Router
{
    /*
     * The route collection instance.
     * */
    public array $routes = [];

    /*
     * The currently dispatched route instance.
     * */
    protected ?Route $route = null;

    /*
     * The request currently being dispatched.
     * */
    protected Request $currentRequest;


    /**
     * Create an instance
     *
     * @retrun void
     * */
    public function __construct(Request $request)
    {
        $this->currentRequest = $request;
        $this->routes = $this->fillRoutes();
    }

    /**
     * Fill the routes collection
     *
     * @return array
     * */
    public function fillRoutes(): array
    {
        return [
            RouteMethod::GET->value => [],
            RouteMethod::POST->value => [],
            RouteMethod::PUT->value => [],
            RouteMethod::DELETE->value => [],
        ];
    }

    /**
     * get the current Request instance
     *
     * @return Request
     * */
    public function getRequestInstance(): Request
    {
        return $this->currentRequest;
    }

    /**
     * Add a new GET route
     *
     * @param string $uri
     * @param callable|array $action
     *
     * @return Route
     */
    public function get(string $uri, callable|array $action): ?Route
    {
        return $this->addRoute($uri, $action, RouteMethod::GET);
    }

    /**
     * Add a new POST route
     *
     * @param string $uri
     * @param callable|array $action
     *
     * @return Route
     */
    public function post(string $uri, callable|array $action): ?Route
    {
        return $this->addRoute($uri, $action, RouteMethod::POST);
    }

    /**
     * Add a new PUT route
     *
     * @param string $uri
     * @param callable|array $action
     *
     * @return Route
     */
    public function put(string $uri, callable|array $action): ?Route
    {
        return $this->addRoute($uri, $action, RouteMethod::PUT);
    }

    /**
     * Add a DELETE POST route
     *
     * @param string $uri
     * @param callable|array $action
     *
     * @return Route
     */
    public function delete(string $uri, callable|array $action): ?Route
    {
        return $this->addRoute($uri, $action, RouteMethod::DELETE);
    }

    public function view()
    {
        //TODO implement view later
    }

    /**
     * landing to the current uri
     *
     * @param string $method
     * @param string $uri
     *
     * @return ?Route
     */
    public function landing(string $method, string $uri): ?Route
    {
        $routes = $this->routes[$method ?? 'GET'];

        if($uri === '')
            $uri = '/';

        foreach ($routes as $route) {
            if($uri === $route['uri']) {
                return $route['instance']->run();
            }
        }

        return null;
    }

    /**
     * Parse thr route
     * */
    public function parseRoute(string $uri = '')
    {
        /*
         * The current url is test/mohamedKhx
         * registerd in the router test/:username
         * */
    }

    /**
     * Run the specific route
     * */
    protected function runRoute()
    {

    }

    /**
     * Check if the route is not already exists in $this->routes
     *
     * @param string $uri
     * @param RouteMethod $method
     *
     * @return bool
     * */
    public function isRouteExists(string $uri, RouteMethod $method): bool
    {
        foreach ($this->routes[$method->value] as $route) {
            if($route['uri'] === $uri) {
                throw new \Exception('The Route is already exists');
                return true;
            }
        }

        return false;
    }

    /**
     * Fix the uri from ['example'] tp ['/example']
     *
     * @param string $uri
     * @return string
     * */
    public function parseUri(string $uri): string
    {
        if(!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        return $uri;
    }

    /**
     * Register a new Route
     *
     * @param string $uri
     * @param callable|array $action
     * @param RouteMethod $method
     *
     * @return Route
     */

    protected function addRoute(string $uri, callable|array $action, RouteMethod $method): ?Route
    {
        $uri = $this->parseUri($uri);

        if($this->isRouteExists($uri, $method)) return null;

        $routeInstance = new Route(
            uri: $uri,
            method: $method,
            action: $action,
            router: $this
        );

        $this->routes[$method->value][] = [
            'uri' => $uri,
            'name' => &$routeInstance->routeName,
            'instance' => $routeInstance
        ];

        return $routeInstance;
    }

}