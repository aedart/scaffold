<?php namespace Aedart\Scaffold\Contracts;

/**
 * <h1>Handler Aware</h1>
 *
 * Component is aware of some kind of a "handler" class path,
 * which is responsible for handling something.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface HandlerAware {

    /**
     * Set the given handler
     *
     * @param string $path Handler - Class path to some kind of a "handler"
     *
     * @return void
     */
    public function setHandler($path);

    /**
     * Get the given handler
     *
     * If no handler has been set, this method will
     * set and return a default handler, if any such
     * value is available
     *
     * @see getDefaultHandler()
     *
     * @return string|null handler or null if none handler has been set
     */
    public function getHandler();

    /**
     * Get a default handler value, if any is available
     *
     * @return string|null A default handler value or Null if no default value is available
     */
    public function getDefaultHandler();

    /**
     * Check if handler has been set
     *
     * @return bool True if handler has been set, false if not
     */
    public function hasHandler();

    /**
     * Check if a default handler is available or not
     *
     * @return bool True of a default handler is available, false if not
     */
    public function hasDefaultHandler();
}