<?php

use Xerophy\Framework\Routing\Redirector;

if(!function_exists('redirect')) {
    function redirect(?string $to = null): Redirector
    {
        $redirector = \Xerophy\Framework\Container\Container::$container->getObject(Redirector::class);

        if ($to) {
            $redirector->to($to);
        }

        return $redirector;
    }
}

if(!function_exists('class_basename')) {
    function class_basename($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

if(!function_exists('errors')) {
    function errors(): array|null
    {
        /**
         * @var $session \Xerophy\Framework\Session\Session
         * */
        $session = \Xerophy\Framework\Container\Container::$container->getObject(\Xerophy\Framework\Session\Session::class);
        return $session->getErrors();
    }
}

if(!function_exists('route')) {

    function route(string $routeName, array $params = []): string
    {

        /**
         * @var $router \Xerophy\Framework\Routing\Router
         * */
        $router = \Xerophy\Framework\Container\Container::$container->getObject(\Xerophy\Framework\Routing\Router::class);
        return $router->getRouteByName($routeName)->getUriAndPassParams($params);
    }
}