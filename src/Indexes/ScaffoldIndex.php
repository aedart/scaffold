<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation;
use Aedart\Scaffold\Traits\LocationMaker;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;
use Carbon\Carbon;
use InvalidArgumentException;

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

    // TODO: NOPE... This has gotten way too complex... It would be fast for a large amount of locations,
    // TODO: BUT I do not think this is worth it... Re-implement this entire think, and create a much more
    // TODO: simple implementation - one that can support a direct to json output, which then can be appended
    // TODO: to a file...


    /**
     * Index key for registered vendors
     */
    const VENDORS_KEY = 'vendors';

    /**
     * Expires key
     */
    const EXPIRES_KEY = 'expires';

    /**
     * Total count key
     */
    const COUNT_KEY = 'total';

    /**
     * Raw key
     * Used for populate method
     *
     * @see populate()
     */
    const RAW_KEY = 'raw';

    /**
     * ScaffoldIndex constructor.
     *
     * @param ScaffoldLocation[] $locations [optional]
     */
    public function __construct(array $locations = [])
    {
        $this->setupDefaultKeys();
        $this->populate($locations);
    }

    /**
     * Setup a few default entries
     */
    protected function setupDefaultKeys()
    {
        $collection = $this->getInternalCollection();

        $collection->put(self::VENDORS_KEY, []);
        $collection->put(self::EXPIRES_KEY, null);
        $collection->put(self::COUNT_KEY, 0);
        $collection->put(self::RAW_KEY, 1);
    }

    public function register(ScaffoldLocation $location)
    {
        $collection = $this->getInternalCollection();

        // Register vendor
        $this->registerVendor($location->getVendor());

        // Register vendor's package(s)
        $this->registerVendorPackage($location->getVendor(), $location->getPackage());

        // Register package's scaffold(s)
        $locationKey = $this->makeLocationKey($location);
        $this->registerPackageScaffoldKey($location->getVendor(), $location->getPackage(), $locationKey);

        // Increase count
        $count = $collection->get(self::COUNT_KEY, 0);
        $count++;
        $collection->put(self::COUNT_KEY, $count);

        // Finally, add the actual scaffold
        return $collection->put($locationKey, $location);
    }

    /**
     * Registers the given vendor, if it does not already exist
     *
     * @param string $vendor
     */
    protected function registerVendor($vendor)
    {
        $collection = $this->getInternalCollection();

        $vendors = $this->getVendors();

        if(!in_array($vendor, $vendors)){
            $vendors[] = $vendor;
            $collection->put(self::VENDORS_KEY, $vendors);
        }
    }

    /**
     * Register the a package for the given vendor
     *
     * @param string $vendor
     * @param string $package
     */
    protected function registerVendorPackage($vendor, $package)
    {
        $collection = $this->getInternalCollection();

        $vendorKey = $this->makeVendorKey($vendor);
        $packages = $collection->get($vendorKey, []);

        if(!in_array($package, $packages)){
            $packages[] = $package;
            $collection->put($vendorKey, $packages);
        }
    }

    /**
     * Register a scaffold's hash key to the given vendor-package's list
     * of scaffolds keys
     *
     * @param string $vendor
     * @param string $package
     * @param string $locationIndex Hash key of where the scaffold object is located
     */
    protected function registerPackageScaffoldKey($vendor, $package, $locationIndex)
    {
        $collection = $this->getInternalCollection();

        $vendorPackageKey = $this->makePackageKey($vendor, $package);
        $scaffolds = $collection->get($vendorPackageKey, []);

        if(!in_array($locationIndex, $scaffolds)){
            $scaffolds[] = $locationIndex;
            $collection->put($vendorPackageKey, $scaffolds);
        }
    }

    public function hasBeenRegistered(ScaffoldLocation $location)
    {

        $locationIndex = $this->makeLocationKey($location);

        return $this->has($locationIndex);
    }

    public function has($locationIndex)
    {
        $collection = $this->getInternalCollection();

        return $collection->has($locationIndex);
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
        $locationIndex = $this->makeLocationKey($location);

        return $this->remove($locationIndex);
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

        $collection = $this->getInternalCollection();

        /** @var ScaffoldLocation $location */
        $location = $collection->get($locationIndex);

        // Get vendor and package
        $vendor = $location->getVendor();
        $package = $location->getPackage();

        // Unset the location
        $collection->forget($locationIndex);

        // If package hos no more locations, remove it
        $locations = $this->getLocationsFor($vendor, $package);
        if(empty($locations)){
            $this->removePackage($vendor, $package);
        }

        return true;
    }

    public function getVendors()
    {
        return $collection = $this->getInternalCollection()->get(self::VENDORS_KEY, []);
    }


    public function getPackagesFor($vendor)
    {
        return $collection = $this->getInternalCollection()->get($this->makeVendorKey($vendor), []);
    }

    public function getLocationsFor($vendor, $package)
    {
        $output = [];

        $collection = $this->getInternalCollection();

        $packageKey = $this->makePackageKey($vendor, $package);

        $locationKeys = $collection->get($packageKey, []);

        foreach($locationKeys as $key){
            if($collection->has($key)){
                $output[] = $collection->get($key);
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
        // TODO: Implement expires() method.
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
        // TODO: Implement hasExpired() method.
    }



    /**
     * Returns all the scaffold locations that this
     * collection contains
     *
     * @return ScaffoldLocation[]
     */
    public function all()
    {
        // TODO: WRONG - this MUST return all scaffolds - NOT the internal
        // TODO: data structure!!

        // TODO: Also, the toArray method NEEDS to be changed... as well as the serialisation methods?!

        //return $this->getInternalCollection()->toArray();

        dd('@todo - implement this correctly!');
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

        // Perform a raw populate of the collection, if "raw" key is set
        if(isset($data[self::RAW_KEY])){
            $this->populateFromRaw($data);
            return;
        }

        // Perform a normal populate, one location at the time...
        foreach($data as $location){

            if($location instanceof ScaffoldLocation){
                $this->register($location);
                continue;
            }

            if(is_array($location)){
                $this->register($this->makeLocation($location));
                continue;
            }

            throw new InvalidArgumentException(sprintf('Unable to populate scaffold index with "%s"', var_export($location, true)));
        }
    }

    public function count()
    {
        return $this->getInternalCollection()->get(self::COUNT_KEY, 0);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    public function __debugInfo()
    {
        return $this->getInternalCollection()->all();
    }

    /**
     * Returns a hash of the given key
     *
     * @param string|array $key
     *
     * @return string Hash
     */
    protected function generateHash($key)
    {
        $str = $key;
        if(is_array($key)){
            $str = implode('.', $key);
        }

        return hash('sha1', $str);
    }

    /**
     * Returns a hash key that corresponds to the given location
     *
     * @param ScaffoldLocation $location
     *
     * @return string
     */
    protected function makeLocationKey(ScaffoldLocation $location)
    {
        return $this->generateHash([
            $location->getVendor(),
            $location->getPackage(),
            $location->getName()
        ]);
    }

    /**
     * Returns a hash key that corresponds to the given vendor
     *
     * @param string $vendor
     *
     * @return string
     */
    protected function makeVendorKey($vendor)
    {
        return $this->generateHash($vendor);
    }

    /**
     * Returns a hash key that corresponds to the given package
     *
     * @param string $vendor
     * @param string $package
     *
     * @return string
     */
    protected function makePackageKey($vendor, $package)
    {
        return $this->generateHash([
            $vendor,
            $package,
        ]);
    }

    /**
     * Populate this index from raw data
     *
     * @see raw()
     *
     * @param array $data
     */
    protected function populateFromRaw(array $data)
    {
        $this->setInternalCollection($this->getInternalCollection()->make($data));
    }

    /**
     * Remove the given package, from the given vendor
     *
     * @param string $vendor
     * @param string $package
     */
    protected function removePackage($vendor, $package)
    {
        $collection = $this->getInternalCollection();

        // Remove the package key
        $packageKey = $this->makePackageKey($vendor, $package);
        $collection->forget($packageKey);

        // Exclude the package from the vendor's list of packages
        $this->excludePackageFrom($vendor, $package);

        // Check if packages are empty - if so, remove vendor
        $vendorPackages = $this->getPackagesFor($vendor);
        if(empty($vendorPackages)){
            $this->excludeVendor($vendor);
        }
    }

    /**
     * Removes the given package from the vendor's list
     * of available packages
     *
     * @param string $vendor
     * @param string $packageToExclude
     */
    protected function excludePackageFrom($vendor, $packageToExclude)
    {
        $collection = $this->getInternalCollection();

        $vendorKey = $this->makeVendorKey($vendor);

        $vendorPackages = $this->getPackagesFor($vendor);

        // Reassign vendor's available packages
        $newVendorPackages = [];
        foreach($vendorPackages as $p){
            if($p != $packageToExclude){
                $newVendorPackages[] = $p;
            }
        }

        $collection->put($vendorKey, $newVendorPackages);
    }

    /**
     * Remove the given vendor from the internal list of
     * available vendors
     *
     * @param string $vendor
     */
    protected function excludeVendor($vendor)
    {
        $collection = $this->getInternalCollection();

        $vendorKey = $this->makeVendorKey($vendor);

        $collection->forget($vendorKey);

        // Get list of vendors and re-assign it
        $vendors = $this->getVendors();

        $newVendors = [];
        foreach($vendors as $v){
            if($v != $vendor){
                $newVendors[] = $v;
            }
        }

        $collection->put(self::VENDORS_KEY, $newVendors);
    }
}