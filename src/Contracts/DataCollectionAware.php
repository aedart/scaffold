<?php namespace Aedart\Scaffold\Contracts;

use Aedart\Scaffold\Contracts\Collections\AskablePropertiesCollection;

/**
 * <h1>Data-Collection Aware</h1>
 *
 * Component is aware of a "data" property, which contains an
 * askable-properties collection instance
 *
 * @see AskablePropertiesCollection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface DataCollectionAware {

    /**
     * Set the given data
     *
     * @param AskablePropertiesCollection $collection Data - A collection of askable-properties
     *
     * @return void
     */
    public function setData(AskablePropertiesCollection $collection);

    /**
     * Get the given data
     *
     * If no data has been set, this method will
     * set and return a default data, if any such
     * value is available
     *
     * @see getDefaultData()
     *
     * @return AskablePropertiesCollection|null data or null if none data has been set
     */
    public function getData();

    /**
     * Get a default data value, if any is available
     *
     * @return AskablePropertiesCollection|null A default data value or Null if no default value is available
     */
    public function getDefaultData();

    /**
     * Check if data has been set
     *
     * @return bool True if data has been set, false if not
     */
    public function hasData();

    /**
     * Check if a default data is available or not
     *
     * @return bool True of a default data is available, false if not
     */
    public function hasDefaultData();
}