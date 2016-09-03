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
 * Lists available scaffolds and allows you to select which one to install.
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
            ->setDescription('Lists available scaffolds and allows you to select which one to install')

            // Install options
            ->addOption('show-all', 'a', InputOption::VALUE_NONE, 'Display all available scaffolds, without selecting vendor and package first')

            // Build options
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Path where to build project or resource', getcwd())

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

        // Install the scaffold
        $this->install($location);

        return 0;
    }

    /**
     * Builds (or installs) the scaffold found at the given
     * location
     *
     * @param ScaffoldLocation $location
     */
    public function install(ScaffoldLocation $location)
    {
        // Get the build command
        $buildCmd = $this->getApplication()->find('build');

        // Create the arguments
        $arguments = [
            'config'            =>  $location->getFilePath(),
            '--output'          =>  $this->input->getOption('output'),
        ];

        // Create the input
        $indexInput = new ArrayInput($arguments);

        // Execute the index command
        $buildCmd->run($indexInput, $this->output);
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
        // Obtain locations
        $locations = $this->obtainLocations($index);

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

    public function obtainLocations(Index $index)
    {
        // If the end user desires to see all scaffolds, instead
        // of having to select a vendor and package first, then
        // we simply return all locations from the index
        if($this->input->getOption('show-all') == true){
            return $index->all();
        }

        // Select a vendor
        $vendor = $this->output->choice('Please select a vendor', $index->getVendors());

        // Select a package
        $package = $this->output->choice('Please select a package', $index->getPackagesFor($vendor));

        // Fetch the available locations for vendor and package
        return $index->getLocationsFor($vendor, $package);
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
        if($this->input->getOption('index-force') == true){
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
        $builder = IoC::getInstance()->make(IndexBuilder::class, ['output' => $this->output]);

        $builder->setDirectory($this->input->getOption('index-output'));

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
        // Default name formatting
        $name = $location->getName() . str_repeat(' ', 50 - strlen($location->getName()));

        // If end-user has selected to see all the scaffolds,
        // then it is very useful to include the vendor and package
        if($this->input->getOption('show-all') == true){
            $name = trim($name) . ' (' . $location->getVendor() . '/' . $location->getPackage() . ')';
            $name = $name . str_repeat(' ', 50 - strlen($name));
        }

        // Return the label
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
Lists available scaffolds and allows you to select which one to install.

Usage:

<info>php scaffold install</info>

The above command will first invoke the <info>index</info> command and thereafter the allow you
to select a <info>vendor</info>, a <info>package</info> from that vendor and finally it will
display a list of available scaffolds inside the selected package.

Once you have selected the desired scaffold, the <info>build</info> command will be invoked and
the scaffold processed and built.

<info>php scaffold install --show-all</info> | <info>php scaffold install -a</info> 

Alternatively, if you wish to skip selecting a vendor and package first, then you can list
all available scaffolds. This is achieved via the <info>--show-all</info>.
EOT;
    }
}