<?php namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Containers\IoC;
//use Aedart\Scaffold\Indexes\IndexBuilder;
use Aedart\Scaffold\Contracts\Builders\IndexBuilder;
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
            ->addOption('directories', 'd', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Locations where to search for *.scaffold.php files', $this->directories())
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force build a new index file')
            ->addOption('expire', 'e', InputOption::VALUE_OPTIONAL, 'When should the index expire. Value stated in minutes.', 5);
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

        /** @var IndexBuilder $builder */
        $builder = (IoC::getInstance())->make(IndexBuilder::class, ['output' => $this->output]);

        $builder->build($this->input->getOption('directories'), $this->input->getOption('force'), $this->input->getOption('expire'));

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