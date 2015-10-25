<?php namespace Aedart\Scaffold\Traits;

/**
 * <h1>Directory Handler Trait</h1>
 *
 * @see \Aedart\Scaffold\Contracts\DirectoryHandlerAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait DirectoryHandlerTrait {

    /**
     * Directory Handler - Class path to a "handler"
     *
     * @var string|null
     */
    protected $directoryHandler = null;

    /**
     * Set the given directory handler
     *
     * @param string $path Directory Handler - Class path to a "handler"
     *
     * @return void
     */
    public function setDirectoryHandler($path) {
        $this->directoryHandler = (string) $path;
    }

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
    public function getDirectoryHandler() {
        if (!$this->hasDirectoryHandler() && $this->hasDefaultDirectoryHandler()) {
            $this->setDirectoryHandler($this->getDefaultDirectoryHandler());
        }
        return $this->directoryHandler;
    }

    /**
     * Get a default directory handler value, if any is available
     *
     * @return string|null A default directory handler value or Null if no default value is available
     */
    public function getDefaultDirectoryHandler() {
        return null;
    }

    /**
     * Check if directory handler has been set
     *
     * @return bool True if directory handler has been set, false if not
     */
    public function hasDirectoryHandler() {
        return !is_null($this->directoryHandler);
    }

    /**
     * Check if a default directory handler is available or not
     *
     * @return bool True of a default directory handler is available, false if not
     */
    public function hasDefaultDirectoryHandler() {
        return !is_null($this->getDefaultDirectoryHandler());
    }
}