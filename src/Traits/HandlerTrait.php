<?php namespace Aedart\Scaffold\Traits;

/**
 * <h1>Handler Trait</h1>
 *
 * @see \Aedart\Scaffold\Contracts\HandlerAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait HandlerTrait {

    /**
     * Handler - Class path to some kind of a "handler"
     *
     * @var string|null
     */
    protected $handler = null;

    /**
     * Set the given handler
     *
     * @param string $path Handler - Class path to some kind of a "handler"
     *
     * @return void
     */
    public function setHandler($path) {
        $this->handler = (string) $path;
    }

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
    public function getHandler() {
        if (!$this->hasHandler() && $this->hasDefaultHandler()) {
            $this->setHandler($this->getDefaultHandler());
        }
        return $this->handler;
    }

    /**
     * Get a default handler value, if any is available
     *
     * @return string|null A default handler value or Null if no default value is available
     */
    public function getDefaultHandler() {
        return null;
    }

    /**
     * Check if handler has been set
     *
     * @return bool True if handler has been set, false if not
     */
    public function hasHandler() {
        return !is_null($this->handler);
    }

    /**
     * Check if a default handler is available or not
     *
     * @return bool True of a default handler is available, false if not
     */
    public function hasDefaultHandler() {
        return !is_null($this->getDefaultHandler());
    }
}