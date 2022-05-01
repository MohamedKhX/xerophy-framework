<?php

namespace Xerophy\Framework\Routing;

use Exception;
use Xerophy\Framework\Container\Container;
use Xerophy\Framework\View\View;

class Route
{
    /**
     * The URI route is referred to
     * */
    protected string $uri;

    /**
     * The route name
     * */
    public ?string $routeName = null;

    /**
     * The route parameters
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

    /*
     * The views folder path
     * */
    protected ?string $viewsPath = null;

    /*
     * The tasks will be run after the route has been parsed
     * */
    protected ?array $queue = [];

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
        $this->parseParamsValues();

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
        $this->callTaskByName('before');

        $this->parseAction();

        if($this->isController()) {
            $this->runFromController();
        } else {
            $this->runCallback();
        }

        $this->runQueue();

        $this->callTaskByName('after');
        return $this;
    }

    /**
     * Run route action
     *
     * @return void
     * */
    protected function runCallback(): void
    {
        call_user_func_array($this->action, [...$this->methodInject($this->action) ,...$this->params]);
    }

    /**
     * Run route action from a controller
     *
     * @return void
     * */
    protected function runFromController(): void
    {
        $method = $this->action;
        $this->controller->$method(...$this->methodInject($method), ...$this->params);

    }

    /**
     * inject the passed classes to the method or function
     *
     * @param string|callable $method
     * @return array
     * */
    protected function methodInject(string|callable $method): array
    {
        if($this->isController()) {
            $reflectionMethod = new \ReflectionMethod($this->controller, $method);
        } else {
            $reflectionMethod = new \ReflectionFunction($method);
        }


        $params = $reflectionMethod->getParameters();

        return array_filter(
            array_map(
                function (\ReflectionParameter $parameter) {

                    $paramType = $parameter->getType();
                    $paramName = $parameter->getName();

                    if($paramType instanceof \ReflectionNamedType) {
                        return Container::$container->getObject($paramType->getName());
                    }

                    return null;

                }, $params),
            fn($item) => !is_null($item)
        ) ;
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
     * Add name for the route
     *
     * @return static;
     *
     * @throws Exception
     */
    public function name(string $name): static
    {
        //Check if the name is not already in use
        if($this->router->isRouteNameExists($name)) {
            throw new Exception('The Route name is already in use');
        }

        $this->routeName = $name;
        return $this;
    }

    /**
     * Get the route name
     *
     * @return ?string
     * */
    public function getName(): ?string
    {
        return $this->routeName;
    }

    /**
     * Get the route uri $this->uri
     *
     * @return string
     * */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the route uri with passed params
     * This method only for routes that has params
     *
     * @param array $params
     * @return string
     * */
    public function getUriAndPassParams(array $params): string
    {
        //test/:test
        $explodedUri = explode('/', $this->getUri());
        $finelUri = [];

        foreach ($explodedUri as $uriCute) {
            if($uriCute === '') continue;

            if(str_contains($uriCute, ':')) {
                $finelUri[] = $params[substr($uriCute, 1)] ?? '';
                continue;
            }

            $finelUri[] = $uriCute;
        }

        return join('/', $finelUri);
    }

    /**
     * get exploded uri
     *
     * @retrun array
     * */
    public function getExplodedUri(): array
    {
        $arr = explode('/', $this->getUri());
        array_shift($arr);

        return $arr;
    }

    /**
     * sleep for some time and after that run the route action
     *
     * @param int $seconds
     * @return static
     */
    public function sleep(int $seconds): static
    {
        $this->registerNewTask('sleep', 'sleepTask', [
            'seconds' => $seconds
        ]);
        return $this;
    }

    /**
     * Register a task for sleep in the queue
     *
     * @param int $seconds
     * @return void
     */
    protected function sleepTask(int $seconds): void
    {
        sleep($seconds);
    }

    /**
     * Return bool of the route has params
     *
     * @return bool
     * */
    public function hasParams(): bool
    {
        return (bool) str_contains($this->uri, ':');
    }

    /**
     * Parse params
     *
     * @return void
     * */
    protected function parseParamsValues(): void
    {
        $routeUri   =  explode('/', $this->uri);
        $requestUri =  explode('/', $this->router->getRequestInstance()->getUrl());

        foreach ($routeUri as $index => $uriCut) {
            if(str_starts_with($uriCut, ':')) {
                $uriCut = str_replace(':', '', $uriCut);
                $this->params[] = $requestUri[$index];
            }
        }

    }

    /**
     * render a view
     *
     * @param string $fileName
     * @param array  $params
     *
     * @return void
     * */
    public function view(string $fileName, array $params = []): static
    {
        $this->registerNewTask('viewTask', 'viewTask', [
            'fileName' => $fileName,
            'params' => $params
        ]);

        return $this;
    }

    protected function viewTask(string $fileName, array $params = [])
    {
        $params = array_combine($params, $this->params);

        $view = new View($this->viewsPath, $fileName, $params);
        echo $view->render();
    }

    /**
     * Set views path
     *
     * @param string $path
     * @return Route
     *
     * @retrun void
     */
    public function setPath(string $path)
    {
        $this->viewsPath = $path;
        return $this;
    }

    /**
     * Get the route params
     *
     * @return array
     * */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Register a new task in the queue
     *
     * @retrun void
     * */
    protected function registerNewTask(string $taskName, string $method, array $params = [], bool $event = false): void
    {
        $this->queue[$taskName] = [
            'method' => $method,
            'params' => $params,
            'event' => $event
        ];
    }

    /**
     * Run the task from the queue
     *
     * @return void
     * */
    protected function runQueue(): void
    {
        foreach ($this->queue as $task) {
            if($task['event'] === true) continue;

            $method = $task['method'];
            $this->$method(...$task['params']);
        }
    }

    /**
     * Call specific task from the queue by name
     *
     * @param string $taskName
     * */
    protected function callTaskByName(string $taskName)
    {
        if(!isset($this->queue[$taskName])) return;

        $taskMethod = $this->queue[$taskName]['method'];
        $taskParams = $this->queue[$taskName]['params'];

        $this->$taskMethod(...$taskParams);
    }

    /**
     * Run a callback function after parsing
     *
     * @param callable $function
     *
     * @return static
     * */
    public function after(callable $function): static
    {
        $this->registerNewTask('after', 'afterTask', [
            'function' => $function,
            'params' => $this->params
        ], true);

        return $this;
    }

    /**
     * Register after task in the queue
     *
     * @param callable $function
     * @param array $params
     *
     * @return void
     */
    protected function afterTask(callable $function, array $params): void
    {
        $function($params);
    }

    /**
     * Run a callback function before parsing
     *
     * @param callable $function
     *
     * @return static
     * */
    public function before(callable $function): static
    {
        $this->registerNewTask('before', 'beforeTask', [
            'function' => $function,
            'params' => &$this->params
        ], true);
        return $this;
    }

    /**
     * Register before task in the queue
     *
     * @param callable $function
     * @param array $params
     *
     * @return void
     */
    protected function beforeTask(callable $function, array &$params)
    {
        $function($params);
    }
}