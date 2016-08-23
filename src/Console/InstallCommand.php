<?php

namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Builders\IndexBuilder;
use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation;
use Aedart\Scaffold\Traits\DirectoriesToIndex;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;

/**
 * Install Command
 *
 * TODO: Desc...
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class InstallCommand extends BaseCommand
{
    use FileTrait;
    use DirectoriesToIndex;

    protected function configure()
    {
        $this
            // Name and desc.
            ->setName('install')
            ->setDescription('TODO...')

            // Index options
            ->addOption('index-directories', 'd', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Locations where to search for *.scaffold.php files', $this->directories())
            ->addOption('index-force', 'f', InputOption::VALUE_NONE, 'Force build a new index file')
            ->addOption('index-expire', 'e', InputOption::VALUE_OPTIONAL, 'When should the index expire. Value stated in minutes.', 5)
            ->addOption('index-output', 'i', InputOption::VALUE_OPTIONAL, 'Directory where to build index', IndexBuilder::DEFAULT_SCAFFOLD_INDEX_DIRECTORY)

            // Help
            ->setHelp($this->formatHelp());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Build index (if needed)
        $index = $this->index();

        $this->output->title('Install...');

        // Get the scaffold location
        $location = $this->askForScaffoldLocation($index);

        // TODO: send scaffold file location to build command...
        $this->output->text($location);
    }

    /**
     * Aks the user to pick the desired scaffold to be installed
     * and return it's location
     *
     * @param Index $index
     *
     * @return ScaffoldLocation
     */
    public function askForScaffoldLocation(Index $index)
    {
        // Select a vendor
        $vendor = $this->output->choice('Please select a vendor', $index->getVendors());

        // Select a package
        $package = $this->output->choice('Please select a package', $index->getPackagesFor($vendor));

        // Fetch the available locations for vendor and package
        $locations = $index->getLocationsFor($vendor, $package);

        // Create a map between the scaffold locations and what will be displayed
        // to the end user as a choice
        $map = [];
        $installableScaffolds = [];
        foreach ($locations as $location){
            $label = $this->formatLabel($location);
            $map[$this->hash($label)] = $location;
            $installableScaffolds[] = $label;
        }

        // Select a scaffold (location)
        $selectedLabel = $this->output->choice('Please select a scaffold', $installableScaffolds);

        return $map[$this->hash($selectedLabel)];
    }

    /**
     * Searches for *.scaffold.php files and indexes them,
     * if needed (no previous index or index is expired)
     *
     * @see \Aedart\Scaffold\Console\IndexCommand
     *
     * @return Index
     */
    public function index()
    {
        // Get the index command
        $indexCmd = $this->getApplication()->find('index');

        // Create the arguments
        $arguments = [
            '--directories'     =>  $this->input->getOption('index-directories'),
            '--expire'          =>  $this->input->getOption('index-expire'),
            '--output'          =>  $this->input->getOption('index-output'),
        ];

        // Additionally, check if force index is required
        $force = $this->input->getOption('index-force');
        if($force){
            $arguments['--force'] = true;
        }

        // Create the input
        $indexInput = new ArrayInput($arguments);

        // Execute the index command
        $indexCmd->run($indexInput, $this->output);

        // Return the index
        return $this->getIndex();
    }

    /**
     * Returns a populated index
     *
     * @return Index
     */
    public function getIndex()
    {
        /** @var IndexBuilder $builder */
        $builder = (IoC::getInstance())->make(IndexBuilder::class, ['output' => $this->output]);
        return $builder->load();
    }

    /**
     * Formats a display label for the given scaffold location
     *
     * @param ScaffoldLocation $location
     *
     * @return string
     */
    protected function formatLabel(ScaffoldLocation $location)
    {
        $name = $location->getName() . str_repeat(' ', 50 - strlen($location->getName()));

        return $name . $location->getDescription();
    }

    /**
     * Returns a hash representation of the given value
     *
     * @param string $value
     *
     * @return string
     */
    protected function hash($value)
    {
        return md5($value);
    }

    /**
     * Formats and returns this commands help text
     *
     * @return string
     */
    protected function formatHelp()
    {
        return <<<EOT
TODO ...
EOT;
    }
}