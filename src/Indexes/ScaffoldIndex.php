<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation;
use Aedart\Scaffold\Exceptions\CannotPopulateIndexException;
use Aedart\Scaffold\Traits\LocationMaker;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;
use Carbon\Carbon;
use DateTime;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function hasBeenRegistered(ScaffoldLocation $location)
    {
        return $this->has($location->hash());
    }

    /**
     * {@inheritdoc}
     */
    public function has($locationIndex)
    {
        return $this->getInternalCollection()->has($locationIndex);
    }

    /**
     * {@inheritdoc}
     */
    public function unregister(ScaffoldLocation $location)
    {
        return $this->remove($location->hash());
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function expires(Carbon $when)
    {
        $this->expiresAt = $when;
    }

    /**
     * {@inheritdoc}
     */
    public function hasExpired()
    {
        $now = new Carbon();

        return $now->gt($this->expiresAt());
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt()
    {
        if(!isset($this->expiresAt)){
            $this->expiresAt = (new Carbon())->addMinutes(self::DEFAULT_EXPIRES_IN);
        }

        return $this->expiresAt;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->getInternalCollection()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function populate(array $data = [])
    {
        foreach ($data as $key => $location){
            if(is_array($location)){
                $this->register($this->makeLocation($location));
                continue;
            }

            if($location instanceof ScaffoldLocation){
                $this->register($location);
                continue;
            }

            // Expires at
            if(is_string($key) && $key == self::EXPIRES_AT_KEY){

                if($location instanceof DateTime){
                    $this->expires($location);
                } else {
                    $this->expires(new Carbon($location));
                }

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
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->getInternalCollection()->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if($offset instanceof ScaffoldLocation){
            $offset = $offset->hash();
        }

        $this->remove($offset);
    }
}