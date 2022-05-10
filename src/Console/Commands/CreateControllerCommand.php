<?php

namespace Xerophy\Framework\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateControllerCommand extends Command
{
    protected static $defaultName = 'make:controller';

    protected static $defaultDescription = 'Creates a new controller.';

    protected string $controllersPath;


    public function __construct(string $controllersPath)
    {
        $this->controllersPath = $controllersPath;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $controllersPath = $this->controllersPath;
        $controllerName  = $input->getArgument('Name');
        $controllerCode  = <<<controller
        <?php
        
        namespace App\Controllers;
        
        class $controllerName extends Controller
        {
            public function index()
            {
                //Start write your code
            }
        }
        controller;

        $fileFullPath = $controllersPath . $controllerName . '.php';

        if(file_exists($fileFullPath)) {
            $output->write('The Controller is already exists');
            return Command::FAILURE;
        } else {
            file_put_contents($fileFullPath, $controllerCode);
        }

        $output->write('Controller created successfully');
        return Command::SUCCESS;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('Name', InputArgument::REQUIRED, 'The Controller Name.')
        ;
    }
}