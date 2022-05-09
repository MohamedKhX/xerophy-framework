<?php

namespace Xerophy\Framework\Routing;

use Xerophy\Framework\Application\Application;
use Xerophy\Framework\Container\Container;
use Xerophy\Framework\View\View;

class Controller
{
    public function render(string $filename, array $params = [])
    {
        $app = Container::$container;

        (new View(
           path: $app->getViewsPath(),
           fileName: $filename,
           params: $params
        ))->render();
    }
}