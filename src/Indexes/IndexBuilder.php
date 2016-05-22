<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Traits\IndexDirectoryPath;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Index Builder
 *
 * @TODO ... description, interface maybe?
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Indexes
 */
class IndexBuilder
{
    use FileTrait;
    use IndexDirectoryPath;

    /**
     * Name of the directory where
     * this index file is going to
     * be located
     */
    const SCAFFOLD_INDEX_DIRECTORY_NAME = '.scaffold/';

    /**
     * Scaffold file name pattern
     */
    const SCAFFOLD_FILE_PATTERN = '*.scaffold.php';

    /**
     * The index content
     *
     * @var array
     */
    protected $index = [];

    /**
     * Locations where to search for
     * scaffold files
     *
     * @var array
     */
    protected $placesToSearch = [];

    /**
     * IndexBuilder constructor.
     */
    public function __construct()
    {
        $this->placesToSearch = [
            getcwd() . DIRECTORY_SEPARATOR,
            // TODO: vendor path, ==> $composerConfig = \Composer\Factory::createConfig(null, getcwd());
            // TODO: global vendor path
        ];
    }

    /**
     * Searches and generates an index file of all the found
     * scaffold configuration files
     */
    public function build()
    {
        // TODO: $force option
        // TODO: do we really need to build - check if exists and if expired...

        // Create scaffold directory if not already exists
        $this->makeIndexDirectory();

        // Git ignore the index directory path
        $this->gitIgnoreIndexDirectory();

        // Search for scaffold files and add them to the index
        foreach($this->placesToSearch as $path){
            $this->index($path);
        }

        // Build the index file
        $this->buildIndexFile($this->index);
    }

    /**
     * Searches for scaffold files in the given path and
     * adds them to the index - if any found!
     *
     * @param string $path
     */
    protected function index($path)
    {
        $fs = $this->getFile();

        // Search for *.scaffold.php files
        $scaffolds = $fs->glob( $path . self::SCAFFOLD_FILE_PATTERN);

        // Abort if nothing found
        if(empty($scaffolds)){
            // TODO: output if nothing found
            return;
        }

        // Add each scaffold to the index
        foreach($scaffolds as $scaffoldFile){
            $this->addScaffoldFileToIndex($scaffoldFile);
        }
    }

    /**
     * Add the given scaffold configuration file to the index
     *
     * @param string $file Scaffold configuration file
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function addScaffoldFileToIndex($file)
    {
        $fs = $this->getFile();

        // Load the scaffold file
        $scaffold = $this->loadScaffoldFile($file);

        // Get the name and desc. of the scaffold
        $scaffoldName = $scaffold['name'];
        $scaffoldDescription = $scaffold['description'];

        // Default Package vendor and package name
        $packageVendor = '(no vendor)';
        $packageName = '(no package name)';

        // Set vendor and package from composer file, if available
        $composerFile = $fs->dirname($file) . DIRECTORY_SEPARATOR . 'composer.json';
        if($fs->exists($composerFile)){
            $packageComposer = json_decode($fs->get($composerFile), true);
            $parts = explode('/', $packageComposer['name']);

            $packageVendor = $parts[0];
            $packageName = $parts[1];
        }

        // Add to index
        $this->index[$packageVendor][$packageName][] = [
            'name'          => $scaffoldName,
            'description'   => $scaffoldDescription,
            'file'          => $file
        ];
    }

    /**
     * Persists the content given into an index file
     *
     * Previous index file will be removed before new
     * index file is saved to the disk
     *
     * @param array $content
     */
    protected function buildIndexFile(array $content)
    {
        $fs = $this->getFile();

        // Add expires date
        $content['expiresAt'] = (string) Carbon::now()->addMinutes(30);

        // Path of the index file
        // TODO: ... uhm, name of the index file...
        $indexFile = $this->getIndexDirectoryPath() . 'index.json';

        // Clear any eventual previous index file
        if($fs->exists($indexFile)){
            $fs->delete($indexFile);
        }

        // Write the index file
        $fs->append($indexFile, json_encode($content, JSON_PRETTY_PRINT));
    }

    /**
     * Load the given scaffold file and return its
     * array content
     *
     * @param string $file
     *
     * @return array
     */
    protected function loadScaffoldFile($file)
    {
        return require $file;
    }

    /**
     * Creates a directory of where the index file is
     * going to be located, BUT only if that directory
     * does not exist.
     *
     * @see getIndexDirectoryPath()
     */
    protected function makeIndexDirectory()
    {
        $fs = $this->getFile();

        $scaffoldDirectory = $this->getIndexDirectoryPath();

        if(!$fs->exists($scaffoldDirectory)){
            $fs->makeDirectory($scaffoldDirectory);
        }
    }

    // TODO: What if other components need this path!?... Something that processes the index file!
    public function getDefaultIndexDirectoryPath()
    {
        return getcwd() . DIRECTORY_SEPARATOR . self::SCAFFOLD_INDEX_DIRECTORY_NAME;
    }

    /**
     * Git ignore the index directory path, if it as not
     * already ignored
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function gitIgnoreIndexDirectory()
    {
        $fs = $this->getFile();

        // Abort if no git ignore file exists
        $gitIgnoreFile = '.gitignore';
        if(!$fs->exists($gitIgnoreFile)){
            return;
        }

        // Fetch ignore file content
        $content = $fs->get($gitIgnoreFile);

        // TODO: This smalls a bit bad...
        // TODO: Get only the folder - not entire path
        $pathToIgnore = self::SCAFFOLD_INDEX_DIRECTORY_NAME . '*';

        // Abort if the path already is ignored
        if(Str::contains($content, $pathToIgnore)){
            return;
        }

        // Append to file
        $fs->append($gitIgnoreFile, PHP_EOL . $pathToIgnore);
    }
}