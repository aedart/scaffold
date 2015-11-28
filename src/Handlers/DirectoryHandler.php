<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Handlers\DirectoryHandler as DirectoryHandlerInterface;
use Symfony\Component\Finder\Finder;

/**
 * <h1>Directory Handler</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\DirectoryHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class DirectoryHandler extends BaseHandler implements DirectoryHandlerInterface {

    public function processElement($element) {
        // Find all directories found inside the given element location (max 255 depth)
        $directories = $this->directoriesIn($this->getBasePath() . $element);

        // Create those found directories in the output-path, if doesn't already exist
        $this->makeDirectories($directories, $this->getOutputPath());
    }

    /**
     * Returns a list of all directories found inside the
     * given "root" directory, recursively.
     *
     * NB: max depth is 255!
     *
     * @param string $rootDirectory Path to from where to start searching for directories
     *
     * @return string[] List of directory paths
     */
    public function directoriesIn($rootDirectory){
        $directories = [];

        foreach (Finder::create()->in($rootDirectory)->directories()->depth('<= 255') as $dir) {
            $dirFullPath = $dir->getPathname();;
            $dirRelativePath = str_replace($rootDirectory . '/', '', $dirFullPath);

            $directories[] = $dirRelativePath;
        }

        return $directories;
    }

    /**
     * Creates the given directories inside the given target location
     *
     * If a directory from the given list already exist, in the target
     * location, it is skipped
     *
     * @param string[] $directories List of directories to be created
     * @param string $targetLocation Full path of where the directories are to be created
     *
     * @return void
     */
    public function makeDirectories(array $directories, $targetLocation) {
        if(empty($directories)){
            return;
        }

        // Get filesystem
        $fs = $this->getFile();

        foreach($directories as $directory){
            $directoryToCreate = $targetLocation . $directory;

            // Skip attempting to create directory, if
            // it already exist in the target location
            if($fs->isDirectory($directoryToCreate)){
                continue;
            }

            // Finally, create the directory
            $fs->makeDirectory($directoryToCreate);
        }
    }
}