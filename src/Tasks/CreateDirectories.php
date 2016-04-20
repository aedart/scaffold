<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Resolvers\IoC;

/**
 * Create Directories Task
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class CreateDirectories extends BaseTask
{

    public function performTask()
    {
        // Check if there are any directories to be created
        $foldersKey = 'folders';
        if(!$this->config->has($foldersKey) || count($this->config->get($foldersKey, [])) == 0){
            $this->output->note('No directories to create');
            return;
        }

        // Obtain the directories handler
        $handler = $this->getDirectoriesHandler();

        // Create directories
        $handler->processDirectories(
            $this->parseDirectories($this->config->get($foldersKey))
        );
    }

    /**
     * Parse the given directories paths and return a collection
     *
     * @param array $directories [optional]
     *
     * @return Directories
     */
    protected function parseDirectories(array $directories = [])
    {
        $ioc = IoC::getInstance();

        return $ioc->make(Directories::class, $directories);
    }

    /**
     * Get the directories handler
     *
     * @return DirectoriesHandler
     */
    protected function getDirectoriesHandler(){
        return $this->resolveHandler('handlers.directory');
    }
}