<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Scaffold\Exceptions\CannotProcessPropertyException;

/**
 * Property Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface PropertyHandler
{
    /**
     * Process the given template data property.
     *
     * Method is responsible for somehow process and obtain
     * the property's value, which thereafter can be used in
     * some context. If needed, this method will "ask" the
     * user for a value to be used.
     *
     * @see Property
     *
     * @param Property $property Some kind of a template data property
     *
     * @return void
     *
     * @throws CannotProcessPropertyException
     */
    public function processProperty(Property $property);
}