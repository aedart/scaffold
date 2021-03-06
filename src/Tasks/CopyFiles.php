<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Contracts\Collections\Files;
use Aedart\Scaffold\Contracts\Handlers\FilesHandler;
use Aedart\Scaffold\Containers\IoC;

/**
 * Copy Files Task
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class CopyFiles extends BaseTask
{
    protected $description = 'Copies a set of source files into the output path';

    public function performTask()
    {
        // Check if there are any files to be copied
        $filesKey = 'files';
        if(!$this->config->has($filesKey) || count($this->config->get($filesKey, [])) == 0){
            $this->output->note('No files to copy');
            return;
        }

        // Obtain the files handler
        $handler = $this->getFilesHandler();

        // Copy the files
        $handler->processFiles(
            $this->parseFiles($this->config->get($filesKey))
        );
    }

    /**
     * Parse the given files paths and return a collection
     *
     * @param array $files [optional]
     *
     * @return Files
     */
    protected function parseFiles(array $files = [])
    {
        /** @var Files $collection */
        $collection = IoC::getInstance()->make(Files::class);
        $collection->populate($files);
        
        return $collection;
    }

    /**
     * Get the files handler
     *
     * @return FilesHandler
     */
    protected function getFilesHandler(){
        return $this->resolveHandler('handlers.file');
    }
}