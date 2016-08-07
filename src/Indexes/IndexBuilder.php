<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Traits\IndexDirectoryPath;
use Aedart\Scaffold\Traits\IndexMaker;
use Aedart\Scaffold\Traits\LocationMaker;
use Aedart\Scaffold\Traits\OutputHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Finder\Finder;

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
    use LocationMaker;
    use IndexMaker;
    use OutputHelper;

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
     * Name of index file
     */
    const DEFAULT_INDEX_FILE_NAME = 'index.json';

    /**
     * The index
     *
     * @var Index
     */
    protected $index = [];

    /**
     * Directories where to search for
     * scaffold files
     *
     * @var array
     */
    protected $placesToSearch = [];

    /**
     * IndexBuilder constructor.
     *
     * @param StyleInterface $output [optional]
     */
    public function __construct(StyleInterface $output = null)
    {
        // Set eventual output
        $this->setOutput($output);

        // Make a new empty index
        $this->index = $this->makeIndex();
    }

    /**
     * Searches and generates an index file of all the found
     * scaffold configuration files
     *
     * @see Index::hasExpired()
     *
     * @param string[] $directories Paths where to search for scaffold files
     * @param bool $force [optional] If true, an index file will be build, regardless of expiration date
     *
     * @return Index Either a new index is build or a cached version is used
     */
    public function build(array $directories, $force = false)
    {
        // Get the path to the index file
        $path = $this->getPathToIndexFile();

        // Check if we really need to build a new index
        if(!$force && $this->doesIndexFileExist()){
            $index = $this->loadIndexFromFile($path);

            // If the index has not expired, then return the index
            if(!$index->hasExpired()){
                $this->outputNote('Using cached index (' . $path . ')');
                return $index;
            }
        }

        $this->outputNote('Building new index (' . $path . ')');

        // Create scaffold directory if not already exists
        $this->makeIndexDirectory();

        // Git ignore the index directory path
        $this->gitIgnoreIndexDirectory();

        // Search for scaffold files and add them to the index
        foreach($directories as $path){
            $this->index($path);
        }

        // Build the index file
        $this->buildIndexFile($this->index);

        // Finally, return the index
        return $this->index;
    }

    /**
     * Check if an index file already exists
     *
     * @return bool
     */
    protected function doesIndexFileExist()
    {
        return $this->getFile()->exists($this->getPathToIndexFile());
    }

    /**
     * Returns the path to the index file
     *
     * @return string
     */
    protected function getPathToIndexFile()
    {
        return $this->getIndexDirectoryPath() . self::DEFAULT_INDEX_FILE_NAME;
    }

    /**
     * Removes eventual existing index file
     */
    protected function deleteIndexFile()
    {
        if($this->doesIndexFileExist()){
            $this->getFile()->delete($this->getPathToIndexFile());
        }
    }

    /**
     * Returns a new Index instance, populated with the content
     * from the given index file
     *
     * @param string $pathToIndexFile
     *
     * @return Index
     */
    protected function loadIndexFromFile($pathToIndexFile)
    {
        $content = $this->getFile()->get($pathToIndexFile);

        return $this->makeIndex(json_decode($content, true));
    }

    /**
     * Searches for scaffold files in the given path and
     * adds them to the index - if any found!
     *
     * @param string $path
     */
    protected function index($path)
    {
        $this->outputMessage('Searching in ' . $path);

        // Search for *.scaffold.php files
        $finder = new Finder();
        $finder->files()->name(self::SCAFFOLD_FILE_PATTERN)->in($path)->depth('< 3');

        // Abort if nothing found
        if($finder->count() == 0){
            $this->outputNote('No scaffolds found in ' . $path);
            return;
        }

        // Add each scaffold to the index
        foreach($finder as $file){
            $this->addScaffoldFileToIndex($file->getRealPath());
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
        $this->outputNote('Indexing ' . $file);

        $fs = $this->getFile();

        // Load the scaffold file
        $scaffold = $this->loadScaffoldFile($file);

        // Create a new location instance
        $location = $this->makeLocation();

        // Get and set the name and desc. of the scaffold
        $location->setName($scaffold['name']);
        $location->setDescription($scaffold['description']);

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

        $location->setVendor($packageVendor);
        $location->setPackage($packageName);

        // Set the file
        $location->setFilePath($file);

        // Finally, add the location to the index
        $this->index->register($location);
    }

    /**
     * Persists the index into a file
     *
     * Previous index file will be removed before new
     * index file is saved to the disk
     *
     * @param Index $index
     */
    protected function buildIndexFile(Index $index)
    {
        // Clear any eventual previous index file
        $this->deleteIndexFile();

        // Write the index file
        $this->getFile()->append($this->getPathToIndexFile(), $index->toJson(JSON_PRETTY_PRINT));
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