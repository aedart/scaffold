<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Scaffold\Contracts\Collections\Files;
use Aedart\Scaffold\Exceptions\CannotCopyFileException;

/**
 * Files Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface FilesHandler extends Handler
{
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
    public function processFiles(Files $collection);
}