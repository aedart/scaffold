<?php namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Contracts\Collections\AskablePropertiesCollection;

/**
 * <h1>Data-Collection Trait</h1>
 *
 * @see \Aedart\Scaffold\Contracts\DataCollectionAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait DataCollectionTrait {

    /**
     * Data - A collection of askable-properties
     *
     * @var AskablePropertiesCollection|null
     */
    protected $data = null;

    /**
     * Set the given data
     *
     * @param AskablePropertiesCollection $collection Data - A collection of askable-properties
     *
     * @return void
     */
    public function setData(AskablePropertiesCollection $collection) {
        $this->data = $collection;
    }

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
    public function getData() {
        if (!$this->hasData() && $this->hasDefaultData()) {
            $this->setData($this->getDefaultData());
        }
        return $this->data;
    }

    /**
     * Get a default data value, if any is available
     *
     * @return AskablePropertiesCollection|null A default data value or Null if no default value is available
     */
    public function getDefaultData() {
        return null;
    }

    /**
     * Check if data has been set
     *
     * @return bool True if data has been set, false if not
     */
    public function hasData() {
        return !is_null($this->data);
    }

    /**
     * Check if a default data is available or not
     *
     * @return bool True of a default data is available, false if not
     */
    public function hasDefaultData() {
        return !is_null($this->getDefaultData());
    }
}