<?php

namespace Xerophy\Framework\Routing;

use Exception;
use Xerophy\Framework\Application\Application;
use Xerophy\Framework\Http\Request;
use Xerophy\Framework\View\View;

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
    protected Request $currentRequest;

    /*
     * The views path
     * */
    protected string $viewsPath;

    /*
     * The 404-page path
     * */
    protected string $page404Path;


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
    protected function fillRoutes(): array
    {
        return [
            RouteMethod::GET->value => [],
            RouteMethod::POST->value => [],
            RouteMethod::PUT->value => [],
            RouteMethod::DELETE->value => [],
        ];
    }

    /**
     * Get the currently dispatched route instance.
     *
     * @return ?Route
     * */
    public function getRouteInstance(): ?Route
    {
        return $this->route;
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
     * @return ?Route
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
     * @return ?Route
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
     * @return ?Route
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
     * @return ?Route
     */
    public function delete(string $uri, callable|array $action): ?Route
    {
        return $this->addRoute($uri, $action, RouteMethod::DELETE);
    }

    /**
     * Add a GET route and render the view in the same time
     *
     * @param string $uri
     * @param string $view
     *
     * @return Route
     * */
    public function view(string $uri, string|array $view): Route
    {
        if(is_array($view)) {
            return $this->addRoute($uri, function () {}, RouteMethod::GET)->view($view[0], $view[1]);
        }
        if(is_string($view)) {
            return $this->addRoute($uri, function () {}, RouteMethod::GET)->view($view);
        }
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
            if($this->match($uri, $route['instance'], $method)) {
                $this->route = $route['instance'];
                return $route['instance']->run();
            }
        }
        $this->load404Page();
        return null;
    }

    /**
     * Render a 404 page
     * */
    protected function load404Page()
    {
        $view = new View(
            path: $this->viewsPath,
            fileName: $this->page404Path
        );
        $view->render();
    }

    /**
     * Match the url
     *
     * @param string $url
     * @param Route  $route
     * @param string $method
     *
     * @return bool
     * */
    protected function match(string $uri, Route $route, string $method): bool
    {
        if($route->hasParams()) {
            $explodedUri = explode('/', $uri);
            array_shift($explodedUri);

            if(count($explodedUri) < count($route->getExplodedUri())) {
                return false;
            }

            foreach ($explodedUri as $key => $value) {
                if(!isset($route->getExplodedUri()[$key])) return false;

                if(str_contains($route->getExplodedUri()[$key], ':')) {
                    continue;
                }

                if($value === $route->getExplodedUri()[$key]) {
                    continue;
                } else {
                    return false;
                }
            }

            return true;
        } else {
            if($uri === $route->getUri()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the route is not already exists in $this->routes
     *
     * @param string $uri
     * @param RouteMethod $method
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isRouteExists(string $uri, RouteMethod $method): bool
    {
        foreach ($this->routes[$method->value] as $route) {
            if($route['uri'] === $uri) {
                throw new \Exception('The Route is already exists');
            }
        }

        return false;
    }

    /**
     * Check the route name if it's not already in use
     *
     * @param string $name
     * @return bool
     *
     * @throws Exception
     */
    public function isRouteNameExists(string $name): bool
    {
        foreach ($this->routes as $routeMethod) {
            foreach ($routeMethod as $route) {
                if($name === $route['name']) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get route by name
     *
     * @param string $name
     * @return ?Route
     * */
    public function getRouteByName(string $name): ?Route
    {
        if(!$this->isRouteNameExists($name)) return null;

        foreach ($this->routes[RouteMethod::GET->value] as $key => $value) {
            if($value['name'] === $name) {
                return $value['instance'];
            }
        }

        foreach ($this->routes[RouteMethod::DELETE->value] as $key => $value) {
            if($value['name'] === $name) {
                return $value['instance'];
            }
        }
        return null;
    }

    /**
     * 1- Fix the uri from ['example'] to ['/example']
     * 2- Fix the uri from ['/example/'] to ['example']
     *
     * @param string $uri
     * @return string
     * */
    protected function parseUri(string $uri): string
    {
        if($uri === '') {
            return '/';
        }

        if($uri === '/') return $uri;

        if(!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        if(str_ends_with($uri, '/')) {
            $strLen = strlen($uri);
            $uri = str_split($uri, $strLen - 1)[0];
        }

        return $uri;
    }

    /**
     * Set views path
     *
     * @param string $path
     * @return void
     */
    public function setViewsPath(string $path): void
    {
        $this->viewsPath = $path;
    }

    /**
     * Set 404 path
     *
     * @param string $path
     * @return void
     */
    public function set404Path(string $path): void
    {
        $this->page404Path = $path;
    }

    /**
     * Register a new Route
     *
     * @param string $uri
     * @param callable|array $action
     * @param RouteMethod $method
     *
     * @return ?Route
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

        $routeInstance->setPath($this->viewsPath);

        $this->routes[$method->value][] = [
            'uri' => $uri,
            'name' => &$routeInstance->routeName,
            'instance' => $routeInstance
        ];

        return $routeInstance;
    }

}