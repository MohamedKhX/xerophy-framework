<?php

namespace Xerophy\Framework\Routing;

class Router
{
    /*
     * The route collection instance
     * */
    public array $routes = [];

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