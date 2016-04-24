<?php namespace Aedart\Scaffold\Contracts\Templates\Data;

/**
 * Post Process Aware
 *
 * Component is aware of a callable method, which can
 * be invoked after some property has been processed
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface PostProcessAware
{
    /**
     * Set the given post process
     *
     * @param callable $method Post Process callable method
     *
     * @return void
     */
    public function setPostProcess(callable $method);

    /**
     * Get the given post process
     *
     * If no post process has been set, this method will
     * set and return a default post process, if any such
     * value is available
     *
     * @see getDefaultPostProcess()
     *
     * @return callable|null post process or null if none post process has been set
     */
    public function getPostProcess();

    /**
     * Get a default post process value, if any is available
     *
     * @return callable|null A default post process value or Null if no default value is available
     */
    public function getDefaultPostProcess();

    /**
     * Check if post process has been set
     *
     * @return bool True if post process has been set, false if not
     */
    public function hasPostProcess();

    /**
     * Check if a default post process is available or not
     *
     * @return bool True of a default post process is available, false if not
     */
    public function hasDefaultPostProcess();
}