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
     * The route permeates
     * */

    protected array $params = ['test', 4];

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
     * @param $router
     *
     * @return void
     */
    public function __construct(string $uri, RouteMethod $method, callable|array $action, $router)
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
    public function parseAction() : mixed
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
     * @return void
     */

    public function run() : void
    {
        $this->parseAction();

        if($this->isController()) {
            $this->runFromController();
            return;
        }

        $this->runCallback();
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

    public function getRouteName()
    {
        //Todo Return the route name
    }

    public function name()
    {
        //Todo add name to the route
    }

    //todo write phpDoc
    public function sleep(int $seconds): static
    {
        sleep($seconds);
        return $this;
    }

}