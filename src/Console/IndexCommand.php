<?php namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Cache\CacheHelper;
use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Builders\IndexBuilder;
use Aedart\Scaffold\Traits\CacheConfigurator;
use Aedart\Scaffold\Traits\DirectoriesToIndex;
use Illuminate\Config\Repository;
use Symfony\Component\Console\Input\InputOption;

/**
 * Index Command
 *
 * Builds an index file with the locations of available scaffolds
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class IndexCommand extends BaseCommand
{
    use FileTrait;
    use DirectoriesToIndex;
    use CacheConfigurator;

    protected function configure()
    {
        $this
            ->setName('index')
            ->setDescription('Builds an index file with the locations of available scaffolds')
            ->addOption('directories', 'd', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Locations where to search for *.scaffold.php files', $this->directories())
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force build a new index file')
            ->addOption('expire', 'e', InputOption::VALUE_OPTIONAL, 'When should the index expire. Value stated in minutes.', 5)
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Directory where to build index', IndexBuilder::DEFAULT_SCAFFOLD_INDEX_DIRECTORY)

            // Cache options
            ->addOption('cache', 'c', InputOption::VALUE_OPTIONAL, 'Cache directory, used by the build command', CacheHelper::DEFAULT_CACHE_DIRECTORY)

            ->setHelp($this->formatHelp());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Configure the cache
        $this->configureCache(new Repository(), $this->input->getOption('cache'));

        $this->output->title('Building index');

        /** @var IndexBuilder $builder */
        $builder = IoC::getInstance()->make(IndexBuilder::class);
        $builder->setOutput($this->output);

        $builder->setDirectory($this->input->getOption('output'));

        $builder->build(
            $this->input->getOption('directories'),
            $this->input->getOption('force'),
            $this->input->getOption('expire')
        );

        $this->output->success('Indexing completed');

        return 0;
    }

    /**
     * Formats and returns this commands help text
     *
     * @return string
     */
    protected function formatHelp()
    {
        return <<<EOT
Builds an index file with the location found scaffolds.

Usage:

<info>php scaffold index</info>

The above command will by default search for *.scaffold.php files in several directories.
If you have placed your scaffolds elsewhere, then you can customise what directories to
search, via the <info>directories</info> option, which accepts multiple paths.

<info>php scaffold build -d /home/scaffolds/ -d vendor/</info>

By default, an index will have a certain expiration date. This can be configured via the
<info>--expire</info> option, which allows specifying when the built index should expire.
The value is stated in minutes from the point of execution.
EOT;
    }
}