<?php

namespace Xerophy\Framework\View;

class View
{
    /*
     * The array of view data
     * */
    protected array $params;

    /*
     * The path to the view file
     * */
    protected string $path;

    /*
     * The file name
     * */
    protected string $fileName;

    /*
     * The twig loader
     * */
    protected \Twig\Loader\FilesystemLoader $loader;

    /*
     * The twig environment
     * */
    protected \Twig\Environment $twig;

    /*
     * Twig options ['https://twig.symfony.com/doc/3.x/api.html']
     * */
    protected ?array $twigOptions = [];

    /**
     * Create a new view instance
     *
     * @param string $path
     * @param string $fileName
     * @param array  $params
     * @param array  $twigOptions
     *
     * @return void
     */
    public function __construct(string $path, string $fileName, array $params = [], $twigOptions = [])
    {
        $this->path = $path;
        $this->fileName = $fileName;
        $this->params = $params;

        $this->twigOptions = $twigOptions;
        $this->loader = new \Twig\Loader\FilesystemLoader($this->path);
        $this->twig = new \Twig\Environment($this->loader, $this->twigOptions);

        $routeFunction = new \Twig\TwigFunction('route', function (string $routeName, array $params = []) {
            /**
             * @var $router \Xerophy\Framework\Routing\Router
             * */
            $router = \Xerophy\Framework\Container\Container::$container->getObject(\Xerophy\Framework\Routing\Router::class);
            return $router->getRouteByName($routeName)->getUriAndPassParams($params);
        });

        $errorsFunction = new \Twig\TwigFunction('errors', function () {
            return errors();
        });

        $methodFunction = new \Twig\TwigFunction('method', function (string $method) {

            if($method === 'DELETE' || $method === 'delete') {
                echo '<input name="_method" hidden type="text" value="DELETE">';
                return;
            }
            if($method === 'PUT' || $method === 'put') {
                echo '<input name="_method" hidden type="text" value="PUT">';
                return;
            }

            throw new \Exception('The method is not supported');
        });

        $this->twig->addFunction($routeFunction);
        $this->twig->addFunction($errorsFunction);
        $this->twig->addFunction($methodFunction);
    }

    /**
     * render the view content
     *
     * @return void
     * */
    public function render(): void
    {
        echo $this->twig->render($this->fileName, $this->params);
    }
}