<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Exceptions\CannotCreateDirectoryException;

/**
 * Directory Handler
 *
 * @see \Aedart\Scaffold\Contracts\Collections\Directories
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class DirectoriesHandler extends BaseHandler implements DirectoriesHandlerInterface
{

    const MODE = 0755;

    /**
     * Perform the actual element processing
     *
     * @param mixed $element
     *
     * @return void
     */
    public function processElement($element)
    {
        /** @var Directories $collection */
        $collection = $element;

        foreach($collection->all() as $path){
            $this->createDirectory($path);
        }
    }

    /**
     * Creates the given directory if it does not already exist
     *
     * @param string $path
     *
     * @throws CannotCreateDirectoryException
     */
    protected function createDirectory($path){
        // Build the final path
        $finalPath = $this->getOutputPath() . $path;

        // Get the log and file system
        $log = $this->getLog();
        $fs = $this->getFile();

        // Check if already exists
        if($fs->exists($finalPath)){
            $log->notice(sprintf('skipped "%s", folder already exists', $finalPath));
            return;
        }

        // Create the directory
        $hasCreated = $fs->makeDirectory($finalPath, self::MODE, true);

        // Check if directory was NOT created
        if(!$hasCreated){
            throw new CannotCreateDirectoryException(sprintf('Cannot create directory; %s', $finalPath));
        }

        // Finally, log the output
        $log->info(sprintf('created "%s"', $finalPath));
    }

    /**
     * Creates the given directories inside the output path,
     * if the directories do not already exist
     *
     * @param Directories $collection List of directory paths
     *
     * @return void
     *
     * @throws CannotCreateDirectoryException
     */
    public function processDirectories(Directories $collection)
    {
        $this->process($collection);
    }
}