<?php namespace Aedart\Scaffold\Handlers\Utility;

use Aedart\Scaffold\Contracts\Handlers\PropertyHandler;

/**
 * Property Handler Resolver
 *
 * A utility that resolves a new property handler instance
 * from the IoC
 *
 * WARNING: This trait can only be used by instances that
 * inherit from BaseTask
 *
 * @see \Aedart\Scaffold\Tasks\BaseTask
 * @see \Aedart\Scaffold\Handlers\PropertyHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers\Utility
 */
trait PropertyHandlerResolver
{
    /**
     * Get the property handler
     *
     * @see \Aedart\Scaffold\Handlers\PropertyHandler
     *
     * @param string $key The "key" or "index" inside the configuration repository,
     *                           in which the final processed value must be stored.
     *
     * @return PropertyHandler
     */
    public function makePropertyHandler($key)
    {
        return $this->resolveHandler('handlers.property', [
            'config'    => $this->config,
            'key'       => $key,
            'output'    => $this->output
        ]);
    }
}