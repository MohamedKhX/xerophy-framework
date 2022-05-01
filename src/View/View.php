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