<?php

namespace Xerophy\Framework\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModelCommand extends Command
{
    protected static $defaultName = 'make:model';

    protected static $defaultDescription = 'Creates a new model.';

    protected string $modelsPath;

    public function __construct(string $modelsPath)
    {
        $this->modelsPath = $modelsPath;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $modelsPath = $this->modelsPath;
        $modelName  = $input->getArgument('Name');
        $modelCode  = <<<model
        <?php
        
        namespace App\Models;
        
        class $modelName extends Model
        {
            
        }
        model;

        $fileFullPath = $modelsPath . $modelName . '.php';

        if(file_exists($fileFullPath)) {
            $output->write('The Model is already exists');
            return Command::FAILURE;
        } else {
            file_put_contents($fileFullPath, $modelCode);
        }

        $output->write('Model created successfully');
        return Command::SUCCESS;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('Name', InputArgument::REQUIRED, 'The Model Name.')
        ;
    }
}