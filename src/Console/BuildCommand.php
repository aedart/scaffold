<?php namespace Aedart\Scaffold\Console;

use Aedart\Scaffold\Traits\ConfigLoader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Build Command
 *
 * Builds folders and files, based on the given configuration
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class BuildCommand extends BaseCommand
{
    use ConfigLoader;

    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Creates folders, copies and generate files into the given output path')
            ->addArgument('config', InputArgument::REQUIRED, 'Path to the scaffold configuration file')
            ->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Path where to build the scaffold folders and files', getcwd());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Load configuration
        $config = $this->getConfigLoader()->parse($this->input->getArgument('config'));

        // Define all of this builder's tasks

        // Execute builder's tasks
    }
}