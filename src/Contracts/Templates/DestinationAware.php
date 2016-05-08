<?php namespace Aedart\Scaffold\Contracts\Templates;

use Aedart\Scaffold\Contracts\Templates\Data\Property;

/**
 * Destination
 *
 * Component is aware of a property that contains a
 * template's destination
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface DestinationAware
{
    /**
     * Set the given destination
     *
     * @param Property $property The destination of a template.
     *
     * @return void
     */
    public function setDestination(Property $property);

    /**
     * Get the given destination
     *
     * If no destination has been set, this method will
     * set and return a default destination, if any such
     * value is available
     *
     * @see getDefaultDestination()
     *
     * @return Property|null destination or null if none destination has been set
     */
    public function getDestination();

    /**
     * Get a default destination value, if any is available
     *
     * @return Property|null A default destination value or Null if no default value is available
     */
    public function getDefaultDestination();

    /**
     * Check if destination has been set
     *
     * @return bool True if destination has been set, false if not
     */
    public function hasDestination();

    /**
     * Check if a default destination is available or not
     *
     * @return bool True of a default destination is available, false if not
     */
    public function hasDefaultDestination();
}