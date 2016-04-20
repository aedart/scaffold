<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Scaffold\Exceptions\CannotCreateDirectoryException;

/**
 * Interface DirectoryHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface DirectoryHandler extends Handler
{

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
    public function processDirectories(Directories $collection);
}