<?php namespace Aedart\Scaffold\Collections;

use Aedart\Scaffold\Contracts\Collections\Files as FilesInterface;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;

/**
 * Files
 *
 * @see \Aedart\Scaffold\Contracts\Collections\Files
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections
 */
class Files implements FilesInterface
{
    use PartialCollectionTrait;

    /**
     * Files constructor.
     *
     * @param string[][] $files [optional]
     */
    public function __construct(array $files = [])
    {
        $this->populate($files);
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
        $this->put($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->getInternalCollection()->forget($offset);
    }

    public function put($sourceFile, $destination)
    {
        return ($this->getInternalCollection()->put($sourceFile, $destination));
    }

    public function all()
    {
        return $this->getInternalCollection()->all();
    }

    public function populate(array $data = [])
    {
        foreach($data as $sourceFile => $destination){
            $this->put($sourceFile, $destination);
        }
    }
}