<?php namespace Aedart\Scaffold\Handlers;
use Aedart\Scaffold\Exceptions\FileAlreadyExistsException;

/**
 * <h1>Copy File Handler</h1>
 *
 * This handler does nothing more than to copy the given
 * element (file) from its source destination and into the
 * given target location (output-path), with the eventual
 * specified filename
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\FileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class CopyFileHandler extends BaseFileHandler{

    public function processElement($element) {
        // Resole the file name - set a default filename, if none has been
        // provided
        $this->resolveFilename($element);

        // Generate the final output path + filename
        $finalOutputFile = $this->getOutputPath() . $this->getFilename();

        // Get the filesystem
        $fs = $this->getFile();

        // Fail, if the given output-file already exists
        if($fs->exists($finalOutputFile)){
            throw new FileAlreadyExistsException(sprintf('File "%s" already exists, will NOT overwrite it', $finalOutputFile));
        }

        // Finally, copy the element file into the specified output-path
        $fs->copy($element, $finalOutputFile);
    }

    /**
     * @param string $element Full path to a given <b>file</b> that must be "handled"
     */
    public function resolveFilename($element) {
        // If a custom filename has been given, then
        // there is no reason for attempting anything
        if($this->hasFilename()){
            return;
        }

        // Get the element's filename and use it
        $filename = $this->getFile()->name($element);

        // Set the filename to use
        $this->setFilename($filename);
    }
}