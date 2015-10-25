<?php namespace Aedart\Scaffold\Collections;

use Aedart\Scaffold\Contracts\Collections\AskablePropertiesCollection as AskablePropertiesCollectionInterface;
use Aedart\Scaffold\Contracts\Data\AskableProperty;
use Aedart\Scaffold\Exceptions\InvalidIdException;
use Aedart\Scaffold\Exceptions\PopulateException;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;
use Illuminate\Support\Facades\App;

/**
 * <h1>Askable-Properties Collection</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Collections\AskablePropertiesCollection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections
 */
class AskablePropertiesCollection implements AskablePropertiesCollectionInterface {

    use PartialCollectionTrait;

    /**
     * Create a new instance of this collection
     *
     * @param AskableProperty[]|array[] $properties [optional]
     */
    public function __construct(array $properties = []) {
        $this->populate($properties);
    }

    public function add(AskableProperty $property) {
        if(!$property->hasId()){
            throw new InvalidIdException('Askable-property has no id set, cannot add to collection');
        }

        $this->getInternalCollection()->put($property->getId(), $property);
    }

    public function get($id){
        return $this->getInternalCollection()->get($id);
    }

    public function has($id){
        return $this->getInternalCollection()->has($id);
    }

    public function remove($id){
        if(!$this->has($id)){
            return;
        }

        $this->getInternalCollection()->forget($id);
    }

    public function offsetExists($offset) {
        return $this->has($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) {
        $this->insert($value);
    }

    public function offsetUnset($offset) {
        $this->remove($offset);
    }

    public function populate(array $data) {
        if(empty($data)){
            return;
        }

        foreach($data as $property){
            $this->insert($property);
        }
    }

    /**
     * Insert a given property into this collection
     *
     * @param array|AskableProperty $property
     *
     * @throws PopulateException
     */
    protected function insert($property){
        if($property instanceof AskableProperty){
            $this->add($property);
            return;
        }

        if(is_array($property)){
            $dto = App::make(AskableProperty::class, $property);
            $this->add($dto);
            return;
        }

        throw new PopulateException(
            sprintf(
                'Cannot populate %s, given entry (%s) must either be instance of %s or a valid array',
                get_class($this),
                var_export($property, true),
                AskableProperty::class
            )
        );
    }
}