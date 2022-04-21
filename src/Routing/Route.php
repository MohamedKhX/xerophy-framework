<?php

namespace Xerophy\Framework\Routing;

use Exception;

class Route
{
    /**
     * The URI route is referred to
     * */
    protected string $uri;

    /**
     * The route name
     * */
    protected ?string $name = null;

    /**
     * The route permeates
     * */

    protected array $params = [];

    /**
     * The HTTP methods the route referred to.
     * */
    protected RouteMethod $method;

    /**
     * The route action
     *
     * @var callable|array
     */
    protected $action;

    /**
     * The controller instance
     */

    protected ?Controller $controller = null;

    /**
     * The router instance used by the route.
     * */
    protected Router $router;


    /**
     * Create a new route instance
     *
     * @param string $uri
     * @param RouteMethod $method
     * @param callable|array $action
     * @param Router $router
     *
     * @return void
     */
    public function __construct(string $uri, RouteMethod $method, callable|array $action, Router $router)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->action = $action;
        $this->router = $router;
    }

    /**
     * Parse the route action to controller and callback
     *
     * @return mixed
     * @throws Exception
     */
    protected function parseAction() : mixed
    {
        if(is_callable($this->action))
            return $this->action;

        if(is_array($this->action)) {

            //$this->>action should look like this [Controller::class , 'methodName']

            $this->getController($this->action[0]);
            return $this->action = $this->action[1];
        }

        throw new Exception('Cannot parse the action');
    }

    /**
     * Run the route action
     *
     * @throws Exception
     * @return static
     */

    public function run() : static
    {
        $this->parseAction();

        if($this->isController()) {
            $this->runFromController();
            return $this;
        }

        $this->runCallback();
        return $this;
    }

    /**
     * Run route action
     *
     * @return void
     * */
    protected function runCallback(): void
    {
        call_user_func_array($this->action, $this->params);
    }

    /**
     * Run route action from a controller
     *
     * @return void
     * */
    protected function runFromController(): void
    {
        $method = $this->action;
        $this->controller->$method(...$this->params);
    }

    /**
     * Checks if the route action called from a controller
     *
     * @return bool
     * */
    protected function isController(): bool
    {
        return (bool) $this->controller;
    }

    /**
     * Get Controller instance
     *
     * @param string $controllerClass
     * @return Controller
     */
    protected function getController(string $controllerClass) : Controller
    {
        $this->controller = new $controllerClass();
        return $this->controller;
    }

    /**
     * Get the HTTP method the route responds to.
     *
     * @return RouteMethod
    */
    public function getRouteMethod(): RouteMethod
    {
        return $this->method;
    }

    /**
     * return the route name
     *
     * @return ?string
     * */
    public function getRouteName(): ?string
    {
        return $this->name;
    }

    /**
     * Add name for the route
     *
     * @return static;
     * */
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * sleep for some time and after that load the route action
     *
     * @param int $seconds
     * @return static
     */
    public function sleep(int $seconds): static
    {
        sleep($seconds);
        return $this;
    }

}