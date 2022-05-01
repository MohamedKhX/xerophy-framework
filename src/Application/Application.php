<?php

namespace Xerophy\Framework\Application;

use Xerophy\Framework\Container\Container;
use Xerophy\Framework\Http\Request;
use Xerophy\Framework\Http\Response;
use Xerophy\Framework\Routing\Redirector;
use Xerophy\Framework\Routing\Router;
use Xerophy\Framework\Routing\UrlGenerator;
use Xerophy\Framework\Session\Session;

class Application extends Container
{
    /*
     * The framework name.
     * */
    const FRAMEWORK_NAME = 'Xerophy';

    /*
     * The framework version.
     * */
    const FRAMEWORK_VERSION = '0.1a';

    /*
     * The base path for xerophy project.
     * */
    protected string $basePath;

    /*
     * The paths for the project
     * */
    protected array $paths;

    /*
     * Indicates if the application has booted.
     * */
    protected bool $booted = false;

    /*
     * The application path
     * */
    protected string $appPath;

    /*
     * The database path
     * */
    protected string $databasePath;

    /*
     * The environment path
     * */
    protected string $envPath;

    /*
     * The environment file to load during bootstrapping.
     * */
    protected string $envFile = '.env';

    /*
     * The request instance.
     * */
    protected Request $request;

    /*
     * The router instance.
     * */
    protected Router $router;

    /*
     * The custom application name defined by the developer.
     * */
    protected string $appName;

    /*
     * The custom application version defined by the developer.
     * */
    protected string $appVersion;

    /**
     * Create a new application instance.
     *
     * @param string $basePath
     * @return void
     * */
    public function __construct(string $basePath ,array $paths)
    {
        session_start();

        $this->basePath = $basePath;
        $this->paths = $paths;

        $this->request = $this->getObject(Request::class);
        $this->router = $this->getObject(Router::class);
        $this->session = $this->getObject(Session::class);

        //$this->setAppName($_ENV['APP_NAME']);
        //$this->setAppVersion($_ENV['APP_VERSION']);

        Container::$container = $this;

        $this->router->setViewsPath($this->getViewsPath());
    }

    /**
     * Get xerophy version.
     *
     * @return string
     * */
    public function frameworkVersion(): string
    {
        return static::FRAMEWORK_VERSION;
    }

    /**
     * Get the application version.
     *
     * @return string
     * */
    public function appVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * Set the application version.
     * */
    public function setAppVersion(string $version): void
    {
        $this->appVersion = $version;
    }

    /**
     * Get the application name.
     *
     * @return string
     * */
    public function appName(): string
    {
        return $this->appName;
    }

    /**
     * Set the application name.
     *
     * @retrun void
     * */
    public function setAppName(string $name): void
    {
        $this->appName = $name;
    }

    /**
     * Bootup the application.
     *
     * @return static
     * */
    public function bootup(): static
    {
        $this->router->landing($this->request->method(), $this->request->getUrl());
        return $this;
    }

    /**
     * Get the router instance.
     * */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Get the views path
     *
     * @return string
     * */
    public function getViewsPath(): string
    {
        return $this->paths['Views'];
    }
}