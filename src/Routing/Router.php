<?php

namespace Xerophy\Framework\Routing;

class Router
{
    /*
     * The route collection instance.
     * */
    protected array $routes = [];

    /*
     * The currently dispatched route instance.
     * */
    protected ?Route $route = null;

    /*
     * The request currently being dispatched.
     * */
    protected $currentRequest;


    /**
     * Add a new GET route
     *
     * @param string $uri
     * @param callable|array $action
     *
     * @return Route
     */
    public function get(string $uri, callable|array $action): Route
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
    public function post(string $uri, callable|array $action): Route
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
    public function put(string $uri, callable|array $action): Route
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
    public function delete(string $uri, callable|array $action): Route
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
     * @return ?Route
     * */
    public function landing($method, $uri): ?Route
    {
        $routes = $this->routes[$method];

        foreach ($routes as $route) {
            if($uri === $route['uri']) {
                return $route->run();
            }
        }

        return null;
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

    protected function addRoute(string $uri, callable|array $action, RouteMethod $method): Route
    {
        $routeInstance = new Route(
            uri: $uri,
            method: $method,
            action: $action,
            router: $this
        );

        $this->routes[$method->value] = [
            'uri' => $uri,
            'name' => null,
            'instance' => $routeInstance
        ];

        return $routeInstance;
    }
}