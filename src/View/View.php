<?php

namespace Xerophy\Framework\View;

class View
{
    public function render()
    {
        $loader = new \Twig\Loader\FilesystemLoader('resources/views/');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
        ]);

        $template = $twig->load('index.html');
    }
}