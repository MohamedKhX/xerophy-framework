<?php

namespace Xerophy\Framework\Routing;

use Xerophy\Framework\Application\Application;
use Xerophy\Framework\Container\Container;
use Xerophy\Framework\View\View;

class Controller
{
    /**
     * Render a view
     *
     * @param string $filename
     * @param array $params
     *
     * @return void
     * */
    public function render(string $filename, array $params = []): void
    {
        $app = Container::$container;
        $view = new View(
            path: $app->getViewsPath(),
            fileName: $filename,
            params: $params
        );
        $view->render();
    }
}