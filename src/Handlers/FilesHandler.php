<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Collections\Files;
use Aedart\Scaffold\Contracts\Handlers\FilesHandler as FilesHandlerInterface;
use Aedart\Scaffold\Exceptions\CannotCopyFileException;

/**
 * Files Handler
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\FilesHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class FilesHandler extends BaseHandler implements FilesHandlerInterface
{
    /**
     * Perform the actual element processing
     *
     * @param mixed $element
     *
     * @return void
     */
    public function processElement($element)
    {
        /** @var Files $collection */
        $collection = $element;

        foreach($collection->all() as $sourceFile => $destination){
            $this->copyFile($sourceFile, $destination);
        }
    }

    /**
     * Copy a file to the given source destination
     *
     * @param string $sourceFile
     * @param string $destination
     *
     * @throws CannotCopyFileException
     */
    protected function copyFile($sourceFile, $destination)
    {
        // Create the final paths
        $finalSource = $this->getBasePath() . $sourceFile;
        $finalDestination = $this->getOutputPath() . $destination;

        // Get the log and file system
        $log = $this->getLog();
        $fs = $this->getFile();

        // Check if file already exists
        if($fs->exists($finalDestination)){
            $log->notice('skipped "{path}", file already exists in "{destination}"', ['path' => $sourceFile, 'destination' => $finalDestination]);
            return;
        }

        // Copy the file
        $hasCopied = $fs->copy($finalSource, $finalDestination);

        // Check if directory was NOT created
        if(!$hasCopied){
            throw new CannotCopyFileException(sprintf('Cannot copy file; %s', $finalSource));
        }

        // Finally, log the output
        $log->info('copied "{path}" into "{destination}"', ['path' => $sourceFile, 'destination' => $finalDestination]);
    }

    /**
     * Copies the given collection of source files into
     * their desired destination
     *
     * @see Files
     *
     * @param Files $collection List of source files and their destinations
     *
     * @return void
     *
     * @throws CannotCopyFileException
     */
    public function processFiles(Files $collection)
    {
        $this->process($collection);
    }
}