<?php

namespace Xerophy\Framework\Routing;


class Redirector
{
    /*
     * The url generator instance
     * */
    protected UrlGenerator $generator;

    /*
     * The session store instance
     * */
    protected $session;

    /**
     * Create a new Redirector instance
     *
     * @param UrlGenerator $generator
     * @return void
     * */
    public function __construct(UrlGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Create a new redirect response to the "home" route.
     *
     * @param int $status
     * @return void
     * */
    public function home(int $status = 302): void
    {

    }

    /**
     * Create a new redirect response to the previous location.
     *
     * @param int $status
     * @param array $headers
     *
     * @return void
     * */
    public function back(int $status = 302, array $headers = []): void
    {
        header(
            'Location: ' . $this->generator->previous(),
            response_code: $status
        );
    }

    /**
     * Create a new redirect response to the current URI.
     *
     * @param int $status
     * @param array $headers
     *
     * @return void
     * */
    public function refresh(int $status = 302, array $headers = []): void
    {
        
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param string $path
     * @param int $status
     * @param array $headers
     *
     * @return void
     * */
    public function to(string $path, int $status = 302, array $headers = []): void
    {
        header(
            'Location: ' . $path,
            response_code: $status
        );
    }

    /**
     * Create a new redirect response to an external URL (no validation).
     *
     * @param string $path
     * @param int $status
     * @param array $header
     *
     * @return void
     * */
    public function away(string $path, int $status = 302, array $header = []): void
    {

    }

    /**
     * Create a new redirect response to a named route.
     *
     * @param int $routeName
     * @param array $params
     * @param int $status
     * @param array $header
     *
     * @return void
     * */
    public function route(string $routeName, array $params = [], int $status = 302, array $header = []): void
    {

    }

    /**
     * Create a new redirect a controller action.
     *
     * @param string|array $action
     * @param array $params
     * @param int $status
     * @param array $headers
     *
     * @return void
     * */
    public function action(string|array $action, array $params = [], int $status = 302, array $headers = []): void
    {

    }

    /**
     * Get the URL generator instance
     *
     * @return UrlGenerator
     * */
    public function getUrlGenerator()
    {
        return $this->generator;
    }

    /**
     * Set the active session
     *
     * @param
     * @return
     * */
    public function setSession($session)
    {
        $this->session = $session;
    }
}