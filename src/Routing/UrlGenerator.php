<?php

namespace Xerophy\Framework\Routing;

use Xerophy\Framework\Http\Request;
use Xerophy\Framework\Session\Session;

class UrlGenerator
{

    /*
     * The request instance
     * */
    protected Request $request;

    /*
     * The router instance
     * */
    protected Router $router;

    /*
     * The session instance
     * */
    protected Session $session;

    /**
     * Create a new URL Generator instance
     *
     * @param Request $request
     * @return void
     * */
    public function __construct(Request $request, Router $router, Session $session)
    {
        $this->request = $request;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * Get the full URL for the current request
     *
     * @return string
     * */
    public function full(): string
    {
        return $this->request->getFullUrl();
    }

    /**
     * Get the current URL for the request.
     *
     * @return string
     */
    public function current(): string
    {
        return $this->request->getUrl();
    }

    /**
     * Get the URL for the previous request.
     *
     * @return string
     */
    public function previous(): ?string
    {
        return $this->session->getPreviosUrl();
    }

    /**
     * Get the URL to a named route.
     *
     * @param string $name
     * @param array $params
     *
     * @return string
     * */
    public function route(string $name, array $params = []): string
    {
        if ($this->router->getRouteByName($name)->hasParams()) {
            return $this->router->getRouteByName($name)->getUriAndPassParams($params);
        }

        return $this->router->getRouteByName($name)->getUri();
    }

    /**
     * Get the URL to a controller action.
     *
     * @return string
     * */
    public function action(string|array $action, $params = []): string
    {

    }
}