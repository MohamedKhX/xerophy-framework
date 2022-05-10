<?php

namespace Xerophy\Framework\Console;

use Xerophy\Framework\Application\Application;
use Xerophy\Framework\Console\Commands\CreateControllerCommand;
use Xerophy\Framework\Console\Commands\CreateModelCommand;

class Commander
{
    /*
     * Xerophy application instance
     * */
    protected Application $xerophy;

    /*
     * Xerophy console Version.
     * */
    const CONSOLE_VERSION = '0.1a';

    /**
     * Create a new Commander instance.
     * */
    public function __construct(Application $application)
    {
        $this->xerophy = $application;

    }

    /**
     * Get console version
     *
     * @return string
     * */
    public function getVersion(): string
    {
        return static::CONSOLE_VERSION;
    }

    /**
     * Get Controllers path from the main application
     *
     * @return string
     * */
    public function getControllersPath(): string
    {
       return $this->xerophy->getPath('Controllers');
    }


    /**
     * Get Models path from the main application
     *
     * @return string
     * */
    public function getModelsPath(): string
    {
        return $this->xerophy->getPath('Models');
    }

    /**
     * Create a new console
     *
     * @return \Symfony\Component\Console\Application
     * */
    public function createConsole(): \Symfony\Component\Console\Application
    {
        $consoleApplication = new \Symfony\Component\Console\Application();

        $consoleApplication->setVersion(static::CONSOLE_VERSION);

        $this->createConsoleCommands($consoleApplication);

        return $consoleApplication;
    }

    public function createConsoleCommands(\Symfony\Component\Console\Application $console)
    {
        $console->add(new CreateControllerCommand($this->getControllersPath()));
        $console->add(new CreateModelCommand($this->getModelsPath()));
    }

    public function run(\Symfony\Component\Console\Application $console)
    {
        $console->run();
    }
}