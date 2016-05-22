<?php namespace Aedart\Scaffold\Contracts\Indexes;

use Aedart\Util\Interfaces\Collections\IPartialCollection;
use Carbon\Carbon;

/**
 * Scaffold Index
 *
 * A collection of scaffold locations
 *
 * @see \Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface Index extends IPartialCollection
{
    /**
     * Register (add) a location to this index / collection
     *
     * @param ScaffoldLocation $location
     *
     * @return bool
     */
    public function register(ScaffoldLocation $location);

    /**
     * Check if the given location has been registered by
     * this index
     *
     * @param ScaffoldLocation $location
     *
     * @return bool
     */
    public function hasBeenRegistered(ScaffoldLocation $location);

    /**
     * Check if a given location exists in this index
     *
     * @param string $locationIndex
     *
     * @return bool
     */
    public function has($locationIndex);

    /**
     * Un-register (remove) the given location from this
     * index
     *
     * @param ScaffoldLocation $location
     *
     * @return bool
     */
    public function unregister(ScaffoldLocation $location);

    /**
     * Remove the location that matches the given location-index
     *
     * @param string $locationIndex
     *
     * @return bool
     */
    public function remove($locationIndex);

    /**
     * Returns a list of all the vendors' names that
     * have been registered by the added scaffold
     * locations
     *
     * @return string[]
     */
    public function getVendors();

    /**
     * Returns a list of all the packages' names
     * that have been registered by the added scaffold
     * locations
     *
     * @param string $vendor Name of vendor
     *
     * @return string[]
     */
    public function getPackagesFor($vendor);

    /**
     * Returns a list of scaffold locations that have been
     * registered for the given vendor and package name
     *
     * @param string $vendor Name of vendor
     * @param string $package Name of package
     *
     * @return ScaffoldLocation[]
     */
    public function getLocationsFor($vendor, $package);

    /**
     * Set an expiration date of this index
     *
     * @param Carbon $when
     */
    public function expires(Carbon $when);

    /**
     * Check if this index has expired
     *
     * @see expires()
     *
     * @return bool
     */
    public function hasExpired();

    /**
     * Returns this index's raw internal data structure
     *
     * NOTE: If you wish to persist this index, then use
     * this method to obtain the internal data structure
     * and save it to a file or perhaps some type of
     * memory caching
     *
     * @return array
     */
    public function raw();

    /**
     * Populate this index from raw data
     *
     * @see raw()
     *
     * @param array $data
     */
    public function populateFromRaw(array $data);

    /**
     * Returns all the scaffold locations that this
     * collection contains
     *
     * @return ScaffoldLocation[]
     */
    public function all();
}