<?php namespace Aedart\Scaffold\Contracts\Collections;

use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Util\Interfaces\Collections\IPartialCollection;

/**
 * Template Data Properties Collection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface TemplateProperties extends IPartialCollection
{
    /**
     * Put a "new" template data property into this
     * collection.
     *
     * If the property already exists inside the
     * collection (if id exists), then it will be
     * overridden by the new property.
     *
     * @param string $id
     * @param Property $property
     *
     * @return bool
     */
    public function put($id, Property $property);

    /**
     * Check if collection has a property with
     * the given id
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id);

    /**
     * Returns the property that has the given id
     *
     * @param string $id
     *
     * @return Property|null
     */
    public function get($id);

    /**
     * Remove the property that has the given id
     *
     * @param string $id
     *
     * @return bool
     */
    public function remove($id);

    /**
     * Get all added source files and their
     * belonging destination
     *
     * @return Property[]
     */
    public function all();
}