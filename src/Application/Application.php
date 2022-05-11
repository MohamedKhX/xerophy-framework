<?php

namespace Xerophy\Framework\Application;

use Symfony\Component\Dotenv\Dotenv;
use Xerophy\Framework\Container\Container;
use Xerophy\Framework\Database\Database;
use Xerophy\Framework\Database\Managers\MySqlManager;
use Xerophy\Framework\Http\Request;
use Xerophy\Framework\Routing\Router;
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

    /*
     * The database instance.
     * */
    protected static Database $database;

    /*
     * The Session instance
     * */
    protected Session $session;

    /**
     * Create a new application instance.
     *
     * @param string $basePath
     * @param array $paths
     *
     * @return void
     */
    public function __construct(string $basePath ,array $paths)
    {
        session_start();

        $this->basePath = $basePath;
        $this->paths = $paths;

        $this->loadEnv();

        $this->request = $this->getObject(Request::class);
        $this->router  = $this->getObject(Router::class);
        $this->session = $this->getObject(Session::class);

        $this->setAppName($_ENV['APP_NAME']);
        $this->setAppVersion($_ENV['APP_VERSION']);

        Container::$container = $this;

        $this->router->setViewsPath($this->getViewsPath());
        $this->router->set404Path($this->getPath('404-page'));

        static::$database = new Database($this->getObject(MySqlManager::class));
        static::$database->init();
    }

    /**
     * Get the database instance.
     * */
    public static function DB(): Database
    {
        return static::$database;
    }

    /**
     * Parse env file
     *
     * @return void
     * */
    public function loadEnv(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load($this->getPath('Env'));
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
     * Boot the application.
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

    /**
     * Get specific path from the main application
     *
     * @param string $pathName
     * @return string
     * */
    public function getPath(string $pathName): string
    {
        return $this->paths[$pathName];
    }
}