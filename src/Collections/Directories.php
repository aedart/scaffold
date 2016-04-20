<?php namespace Aedart\Scaffold\Collections;

use Aedart\Scaffold\Contracts\Collections\Directories as DirectoriesInterface;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;

/**
 * Directories
 *
 * @see \Aedart\Scaffold\Contracts\Collections\Directories
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections
 */
class Directories implements DirectoriesInterface
{
    use PartialCollectionTrait;

    /**
     * Directories constructor.
     *
     * @param array $paths [optional]
     */
    public function __construct(array $paths = [])
    {
        $this->populate($paths);
    }

    public function offsetExists($offset)
    {
        return $this->getInternalCollection()->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getInternalCollection()->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->getInternalCollection()->put($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->getInternalCollection()->forget($offset);
    }

    public function add($path)
    {
        return ($this->getInternalCollection()->push($path));
    }

    public function all()
    {
        return $this->getInternalCollection()->all();
    }

    public function populate(array $data)
    {
        if(empty($data)){
            return;
        }

        foreach($data as $path){
            $this->add($path);
        }
    }
}