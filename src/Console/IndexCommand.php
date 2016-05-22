<?php namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Indexes\IndexBuilder;

/**
 * Index Command
 *
 * TODO... description of command
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class IndexCommand extends BaseCommand
{
    use FileTrait;

    protected function configure()
    {
        $this
            ->setName('index')
            ->setDescription('TODO');
            //->addArgument('config', InputArgument::REQUIRED, 'Path to the scaffold configuration file')
            //->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Path where to build project or resource', getcwd())
            //->setHelp($this->formatHelp());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        $builder = new IndexBuilder();

        $builder->build();

        // TODO: Remove this...
        $this->output->success('Index command...');
    }
}