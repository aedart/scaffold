<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation;
use Aedart\Scaffold\Exceptions\CannotPopulateIndexException;
use Aedart\Scaffold\Traits\LocationMaker;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;
use Carbon\Carbon;

/**
 * Scaffold Index
 *
 * @see \Aedart\Scaffold\Contracts\Indexes\Index
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Indexes
 */
class ScaffoldIndex implements Index
{
    use PartialCollectionTrait;
    use LocationMaker;

    /**
     * Default expires value, stated in minutes
     */
    const DEFAULT_EXPIRES_IN = 5;

    /**
     * Expires at key, for when index is
     * exported to json or populated via
     * an array
     */
    const EXPIRES_AT_KEY = 'expires_at';

    /**
     * Date of when this index expires
     *
     * @var Carbon
     */
    protected $expiresAt = null;

    /**
     * ScaffoldIndex constructor.
     *
     * @param array $locations
     */
    public function __construct(array $locations = [])
    {
        $this->populate($locations);
    }

    /**
     * Register (add) a location to this index / collection
     *
     * @param ScaffoldLocation $location
     *
     * @return bool
     */
    public function register(ScaffoldLocation $location)
    {
        $this->put($location->hash(), $location);
        return true;
    }

    /**
     * Put a new location into this index, placed at the given index
     *
     * @param string $index
     * @param ScaffoldLocation $location
     */
    protected function put($index, ScaffoldLocation $location)
    {
        $this->getInternalCollection()->put($index, $location);
    }

    /**
     * Check if the given location has been registered by
     * this index
     *
     * @param ScaffoldLocation $location
     *
     * @return bool
     */
    public function hasBeenRegistered(ScaffoldLocation $location)
    {
        return $this->has($location->hash());
    }

    /**
     * Check if a given location exists in this index
     *
     * @param string $locationIndex
     *
     * @return bool
     */
    public function has($locationIndex)
    {
        return $this->getInternalCollection()->has($locationIndex);
    }

    /**
     * Un-register (remove) the given location from this
     * index
     *
     * @param ScaffoldLocation $location
     *
     * @return bool
     */
    public function unregister(ScaffoldLocation $location)
    {
        return $this->remove($location->hash());
    }

    /**
     * Remove the location that matches the given location-index
     *
     * @param string $locationIndex
     *
     * @return bool
     */
    public function remove($locationIndex)
    {
        if(!$this->has($locationIndex)){
            return false;
        }

        $this->getInternalCollection()->forget($locationIndex);
        return true;
    }

    /**
     * Returns a list of all the vendors' names that
     * have been registered by the added scaffold
     * locations
     *
     * @return string[]
     */
    public function getVendors()
    {
        $vendors = [];

        $locations = $this->all();
        foreach ($locations as $location){
            $vendors[$location->getVendor()] = true;
        }

        return array_keys($vendors);
    }

    /**
     * Returns a list of all the packages' names
     * that have been registered by the added scaffold
     * locations
     *
     * @param string $vendor Name of vendor
     *
     * @return string[]
     */
    public function getPackagesFor($vendor)
    {
        $packages = [];

        $locations = $this->all();
        foreach ($locations as $location){
            if($location->getVendor() == $vendor){
                $packages[$location->getPackage()] = true;
            }
        }

        return array_keys($packages);
    }

    /**
     * Returns a list of scaffold locations that have been
     * registered for the given vendor and package name
     *
     * @param string $vendor Name of vendor
     * @param string $package Name of package
     *
     * @return ScaffoldLocation[]
     */
    public function getLocationsFor($vendor, $package)
    {
        $output = [];

        $locations = $this->all();
        foreach ($locations as $location){
            if($location->getVendor() == $vendor && $location->getPackage() == $package){
                $output[] = $location;
            }
        }

        return $output;
    }

    /**
     * Set an expiration date of this index
     *
     * @param Carbon $when
     */
    public function expires(Carbon $when)
    {
        $this->expiresAt = $when;
    }

    /**
     * Check if this index has expired
     *
     * @see expires()
     *
     * @return bool
     */
    public function hasExpired()
    {
        $now = new Carbon();

        return $now->gt($this->expiresAt());
    }

    /**
     * Returns the date of when this index expires
     *
     * @return Carbon
     */
    public function expiresAt()
    {
        if(!isset($this->expiresAt)){
            $this->expiresAt = (new Carbon())->addMinutes(self::DEFAULT_EXPIRES_IN);
        }

        return $this->expiresAt;
    }

    /**
     * Returns all the scaffold locations that this
     * collection contains
     *
     * @return ScaffoldLocation[]
     */
    public function all()
    {
        return $this->getInternalCollection()->all();
    }

    /**
     * Populate this component via an array
     *
     * @param array $data Key-value pair, where the key corresponds to a
     * property name and the value to be set, e.g. <p>
     * <pre>
     * [
     *  'myProperty' => 'myPropertyValue',
     *  'myOtherProperty' => 42.5
     * ]
     * </pre>
     * </p>
     *
     * @return void
     *
     * @throws \Exception In case that one or more of the given array entries are invalid
     */
    public function populate(array $data)
    {
        if(empty($data)){
            return;
        }

        foreach ($data as $key => $location){
            if(is_array($location)){
                $this->register($this->makeLocation($location));
                continue;
            }

            if($location instanceof ScaffoldLocation){
                $this->register($location);
                continue;
            }

            if(is_string($key) && $key == self::EXPIRES_AT_KEY){
                $this->expires(new Carbon($location));
                continue;
            }

            throw new CannotPopulateIndexException(sprintf('Unsupported location type: %s', PHP_EOL . var_export($location, true)));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        // NOTE: the toArray ensures that eventual elements' toArray
        // is also invoked!
        $all = $this->getInternalCollection()->toArray();

        // Add the expires at key
        $all[self::EXPIRES_AT_KEY] = (string) $this->expiresAt();

        return $all;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->getInternalCollection()->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if($offset instanceof ScaffoldLocation){
            $offset = $offset->hash();
        }

        $this->remove($offset);
    }
}