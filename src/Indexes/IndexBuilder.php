<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Model\Traits\Strings\DirectoryTrait;
use Aedart\Model\Traits\Strings\FilenameTrait;
use Aedart\Model\Traits\Strings\PatternTrait;
use Aedart\Scaffold\Contracts\Builders\IndexBuilder as IndexBuilderInterface;
use Aedart\Scaffold\Contracts\Indexes\Index;
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
 * @see \Aedart\Scaffold\Contracts\Builders\IndexBuilder
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Indexes
 */
class IndexBuilder implements IndexBuilderInterface
{
    use DirectoryTrait;
    use PatternTrait;
    use FilenameTrait;
    use FileTrait;
    use LocationMaker;
    use IndexMaker;
    use OutputHelper;

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
     * @param int $expires [optional] How many minutes from now should the index expire
     *
     * @return Index Either a new index is build or a cached version is used
     */
    public function build(array $directories, $force = false, $expires = 5)
    {
        // Get the path to the index file
        $path = $this->getPathToIndexFile();

        // Check if we really need to build a new index
        if(!$force && $this->doesIndexFileExist()){
            $index = $this->loadIndexFromFile($path);

            // If the index has not expired, then return the index
            if(!$index->hasExpired()){
                $this->outputNote('Using cached index (' . $path . ')');
                $this->outputMessage('Expires ' . $index->expiresAt());
                return $index;
            }
        }

        $this->outputNote('Building new index (' . $path . ')');

        // Create scaffold directory if not already exists
        $this->makeIndexDirectory();

        // Git ignore the index directory path
        $this->gitIgnoreIndexDirectory();

        // Search for scaffold files and add them to the index
        foreach($directories as $directory){
            $this->index($directory);
        }

        // Set a new expiration date
        $this->index->expires(Carbon::now()->addMinutes($expires));

        // Build the index file
        $this->buildIndexFile($this->index);

        // Finally, return the index
        return $this->index;
    }

    /**
     * Loads and returns an index from the filesystem
     *
     * @return Index|null Index if one exists, null if none exists
     */
    public function load()
    {
        if($this->doesIndexFileExist()){
            return $this->loadIndexFromFile($this->getPathToIndexFile());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDirectory()
    {
        return self::DEFAULT_SCAFFOLD_INDEX_DIRECTORY;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultPattern()
    {
        return self::DEFAULT_SCAFFOLD_FILE_PATTERN;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultFilename()
    {
        return self::DEFAULT_INDEX_FILE;
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
        return $this->getDirectory() . $this->getFilename();
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

        // Abort if directory doesn't exist
        if(!file_exists($path)){
            $this->outputNote('Directory does not exist ' . $path);
            return;
        }

        // Search for *.scaffold.php files
        $finder = new Finder();
        $finder->files()->name($this->getPattern())->in($path)->depth('< 3');

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
     * Returns true if given content appears to be a valid scaffold
     * format.
     *
     * @param mixed $content
     *
     * @return bool
     */
    protected function isValidScaffold($content)
    {
        // TODO: This validation might need to improve...
        // TODO: However, for now, this appears to be the fastest!

        // Must be an array
        if(!is_array($content)){
            return false;
        }

        // Name property must be set
        if(!isset($content['name'])){
            return false;
        }

        return true;
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

        // Skip file if not a valid scaffold
        if(!$this->isValidScaffold($scaffold)){
            $this->outputWarning(sprintf('Cannot index %s, file is not a valid scaffold format', $file));
            return;
        }

        $this->outputNote('Indexing ' . $file);

        // Create a new location instance
        $location = $this->makeLocation();

        // Get and set the name and desc. of the scaffold
        $location->setName($scaffold['name']);
        if(isset($scaffold['description'])){
            $location->setDescription($scaffold['description']);
        }

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

        $scaffoldDirectory = $this->getDirectory();

        if(!$fs->exists($scaffoldDirectory)){
            $fs->makeDirectory($scaffoldDirectory);
        }
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

        // Build the path to be ignored
        $scaffoldDir = str_replace(getcwd(), '', $this->getDirectory());
        $pathToIgnore = $scaffoldDir . '*';

        // Abort if the path already is ignored
        if(Str::contains($content, $pathToIgnore)){
            return;
        }

        // Append to file
        $fs->append($gitIgnoreFile, PHP_EOL . $pathToIgnore);
    }
}