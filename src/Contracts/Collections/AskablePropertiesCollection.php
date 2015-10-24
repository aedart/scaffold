<?php namespace Aedart\Scaffold\Contracts\Collections;

use Aedart\Scaffold\Contracts\Data\AskableProperty;
use Aedart\Scaffold\Exceptions\InvalidIdException;
use Aedart\Util\Interfaces\Collections\IPartialCollection;

/**
 * <h1>Askable-Properties Collection</h1>
 *
 * A collection containing a set of askable-properties
 *
 * @see AskableProperty
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface AskablePropertiesCollection extends IPartialCollection{

    /**
     * Add an askable property to this collection
     *
     * @param AskableProperty $property
     *
     * @return void
     *
     * @throws InvalidIdException If property has no id set
     */
    public function add(AskableProperty $property);

    /**
     * Get an askable-property by its id
     *
     * @param string $id
     *
     * @return AskableProperty|null Returns null if no property exists in collection with given id
     */
    public function get($id);

    /**
     * Check if this collection contains a property
     * with the given id
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id);

    /**
     * Remove the property that has the given id
     *
     * @param string $id
     *
     * @return void
     */
    public function remove($id);
}