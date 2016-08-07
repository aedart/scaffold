<?php namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Indexes\IndexBuilder;
use Composer\Factory;
use Symfony\Component\Console\Input\InputOption;

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
            ->setDescription('TODO')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force build a new index file')
            ->addOption('expire', 'e', InputOption::VALUE_OPTIONAL, 'When should the index expire. Value stated in minutes.', 5);
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
        $this->output->title('Building index');

        $builder = new IndexBuilder($this->output);

        $builder->build($this->directories(), $this->input->getOption('force'), $this->input->getOption('expire'));

        $this->output->success('Indexing completed');
    }

    /**
     * Returns a list of directory paths in which scaffold files
     * are to be searched for, in order to build an index
     *
     * @return string[]
     */
    public function directories()
    {
        $composerConfig = Factory::createConfig(null, getcwd());

        $vendorDir = $composerConfig->get('vendor-dir');
        $globalVendorDir = (Factory::createConfig(null, $composerConfig->get('home')))->get('vendor-dir');

        return [

            // The current working directory of where this command
            // is being executed from
            getcwd() . DIRECTORY_SEPARATOR,

            // The vendor folder inside the current working directory
            $vendorDir,

            // The "global" vendor directory inside the composer home
            $globalVendorDir
        ];
    }
}