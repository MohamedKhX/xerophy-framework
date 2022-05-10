<?php

namespace Xerophy\Framework\Routing;


use Xerophy\Framework\Container\Container;
use Xerophy\Framework\Session\Session;

class Redirector
{
    /*
     * The url generator instance
     * */
    protected UrlGenerator $generator;

    /*
     * The session store instance
     * */
    protected Session $session;

    /**
     * Create a new Redirector instance
     *
     * @param UrlGenerator $generator
     * @return void
     * */
    public function __construct(UrlGenerator $generator)
    {
        $this->generator = $generator;
        $this->session = Container::$container->getObject(Session::class);
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
     * @return static
     * */
    public function back(int $status = 302, array $headers = []): static
    {
        header(
            'Location: ' . $this->generator->previous(),
            response_code: $status
        );

        return $this;
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
            'Location: /' . $path,
            false,
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
     * @param string $routeName
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
     *
     * */
    public function withErrors()
    {
        $this->session->saveErrorsToNextRedirection = true;
    }

    /**
     * Get the URL generator instance
     *
     * @return UrlGenerator
     * */
    public function getUrlGenerator(): UrlGenerator
    {
        return $this->generator;
    }

    /**
     * Set the active session
     *
     * @param Session $session
     * @return void
     * */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }
}