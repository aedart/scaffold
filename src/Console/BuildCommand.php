<?php namespace Aedart\Scaffold\Console;

use Aedart\Config\Loader\Traits\ConfigLoaderTrait;
use Aedart\Scaffold\Cache\CacheHelper;
use Aedart\Scaffold\Traits\CacheConfigurator;
use Aedart\Scaffold\Traits\TaskRunner;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Build Command
 *
 * Executes a series of console tasks, which should be
 * defined in a scaffold configuration
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class BuildCommand extends BaseCommand
{
    use ConfigLoaderTrait;
    use TaskRunner;
    use CacheConfigurator;

    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Executes the tasks that are defined inside the provided scaffold configuration')
            ->addArgument('config', InputArgument::REQUIRED, 'Path to the scaffold configuration file')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Path where to build project or resource', getcwd())
            ->addOption('cache', 'c', InputOption::VALUE_OPTIONAL, 'Cache directory', CacheHelper::DEFAULT_CACHE_DIRECTORY)
            ->addOption('input', 'i', InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, 'Answers to question(s)', [])
            ->setHelp($this->formatHelp());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Set eventual input onto the question helper
        $this->setQuestionHelperInput(
            $this->input->getOption('input')
        );

        // Configure the cache
        $this->configureCache(
            new Repository(),
            $this->input->getOption('cache')
        );

        // Load and resolve the configuration
        $config = $this->loadAndResolveConfiguration(
            $this->input->getArgument('config'),
            $this->input->getOption('output')
        );

        // Execute builder's tasks
        $this->getTaskRunner()->execute(
            $config->get('tasks', []),
            $this->input,
            $this->output,
            $config
        );

        // Output done msg
        $this->output->newLine();
        $this->output->success(sprintf('%s completed', $config->get('name')));

        return 0;
    }

    /**
     * Loads and resolves the scaffold configuration
     *
     * @param string $pathToConfig
     * @param string $outputPath
     *
     * @return Repository|RepositoryInterface
     */
    protected function loadAndResolveConfiguration($pathToConfig, $outputPath)
    {
        // Load configuration
        $config = $this->getConfigLoader()->parse($pathToConfig);

        // Get the filename (without file extension) so that we know what
        // part of the configuration to fetch. The filename acts as the
        // configuration's entry key.
        $filename = strtolower(pathinfo($pathToConfig, PATHINFO_FILENAME));

        // Fetch the content of the configuration and pass it
        // into a new configuration, which is the one that we are
        // going to pass on to each task
        $config = new Repository($config->get($filename));

        // Resolve the output path and added it to the configuration
        if(substr($outputPath, -1) != DIRECTORY_SEPARATOR){
            $outputPath = $outputPath . DIRECTORY_SEPARATOR;
        }
        $config->set('outputPath', $outputPath);

        // Output title and what configuration file is being used
        $this->output->title(sprintf('Building %s', $config->get('name')));
        $this->output->text(sprintf('%s', $config->get('description')));
        $this->output->text(sprintf('Using: %s', $pathToConfig));

        // Finally, return the configuration
        return $config;
    }

    /**
     * Formats and returns this commands help text
     *
     * @return string
     */
    protected function formatHelp()
    {
        return <<<EOT
Execute a series of tasks, which should be defined inside the provided
scaffold configuration.

Usage:

<info>php scaffold build resources/examples/example.scaffold.php</info>

The above command will load and read the specified scaffold configuration file and process it.
All directories and files will be created, copied or generated into the <debug>current working directory</debug>.
You can, however, also specify a desired output directory:

<info>php scaffold build resources/examples/example.scaffold.php --output /home/vagrant/Aedart/MyProject/</info>
EOT;
    }
}