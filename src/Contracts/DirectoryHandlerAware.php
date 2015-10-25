<?php namespace Aedart\Scaffold\Contracts;

/**
 * <h1>Directory-Handler Aware</h1>
 *
 * Component is aware of some kind of a "handler" class path,
 * which is responsible for handling directories
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface DirectoryHandlerAware {

    /**
     * Set the given directory handler
     *
     * @param string $path Directory Handler - Class path to a "handler"
     *
     * @return void
     */
    public function setDirectoryHandler($path);

    /**
     * Get the given directory handler
     *
     * If no directory handler has been set, this method will
     * set and return a default directory handler, if any such
     * value is available
     *
     * @see getDefaultDirectoryHandler()
     *
     * @return string|null directory handler or null if none directory handler has been set
     */
    public function getDirectoryHandler();

    /**
     * Get a default directory handler value, if any is available
     *
     * @return string|null A default directory handler value or Null if no default value is available
     */
    public function getDefaultDirectoryHandler();

    /**
     * Check if directory handler has been set
     *
     * @return bool True if directory handler has been set, false if not
     */
    public function hasDirectoryHandler();

    /**
     * Check if a default directory handler is available or not
     *
     * @return bool True of a default directory handler is available, false if not
     */
    public function hasDefaultDirectoryHandler();
}