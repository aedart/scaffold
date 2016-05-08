<?php namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Contracts\Templates\Data\Property;

/**
 * Destination
 *
 * @see \Aedart\Scaffold\Contracts\Templates\DestinationAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait Destination
{
    /**
     * The destination of a template.
     *
     * @var Property|null
     */
    protected $destination = null;

    /**
     * Set the given destination
     *
     * @param Property $property The destination of a template.
     *
     * @return void
     */
    public function setDestination(Property $property)
    {
        $this->destination = $property;
    }

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
    public function getDestination()
    {
        if (!$this->hasDestination() && $this->hasDefaultDestination()) {
            $this->setDestination($this->getDefaultDestination());
        }
        return $this->destination;
    }

    /**
     * Get a default destination value, if any is available
     *
     * @return Property|null A default destination value or Null if no default value is available
     */
    public function getDefaultDestination()
    {
        return null;
    }

    /**
     * Check if destination has been set
     *
     * @return bool True if destination has been set, false if not
     */
    public function hasDestination()
    {
        return !is_null($this->destination);
    }

    /**
     * Check if a default destination is available or not
     *
     * @return bool True of a default destination is available, false if not
     */
    public function hasDefaultDestination()
    {
        return !is_null($this->getDefaultDestination());
    }
}