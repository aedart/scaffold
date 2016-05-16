<?php namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Carbon\Carbon;
use Composer\Factory;

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
        // TODO: Refactor this into separate class... And make it more testable!
        $fs = $this->getFile();

        // Create scaffold directory if not already exists
        $scaffoldDirectory = getcwd() . DIRECTORY_SEPARATOR . '.scaffold/';
        if(!$fs->exists($scaffoldDirectory)){
            $fs->makeDirectory($scaffoldDirectory);
            // TODO: Add .scaffold to .gitignore, if not already added
        }

        // The index
        $index = [];

        // Create new instance of the composer configuration
        $composerConfig = Factory::createConfig(null, getcwd());

        // TODO ----------------- should search in current work dir, vendor and global vendor --------
        // Search for *.scaffold.php files
        $scaffolds = $fs->glob( getcwd() . DIRECTORY_SEPARATOR . '*.scaffold.php');

        // TODO: output if nothing found

        foreach($scaffolds as $scaffoldFile){
            // Get directory of where the scaffold is located
            $dir = $fs->dirname($scaffoldFile) . DIRECTORY_SEPARATOR;

            // Load the scaffold file
            // TODO: Should perhaps be done via config loader!
            $scaffold = require_once $scaffoldFile;

            // Get the name and desc. of the scaffold
            $scaffoldName = $scaffold['name'];
            $scaffoldDescription = $scaffold['description'];

            // Load the package composer.json file
            $packageComposer = json_decode(file_get_contents($dir . 'composer.json'), true);

            // Package vendor and package name
            $parts = explode('/', $packageComposer['name']);
            $packageVendor = $parts[0];
            $packageName = $parts[1];

            // Add to index
            $index[$packageVendor][$packageName][] = [
                'name'          => $scaffoldName,
                'description'   => $scaffoldDescription,
                'file'          => $scaffoldFile
            ];
        }

        // TODO ----------------- edn search --------

        // Add expires date
        $index['expiresAt'] = (string) Carbon::now()->addMinutes(30);

        //
        // Create index file
        //
        $indexFile = $scaffoldDirectory . 'index.json';

        // Clear previous index file
        $fs->delete($indexFile);

        // Write the index file
        $fs->append($indexFile, json_encode($index, JSON_PRETTY_PRINT));

        //dd($config->all());

        // TODO: Remove this...
        $this->output->success('Index command...');
    }
}