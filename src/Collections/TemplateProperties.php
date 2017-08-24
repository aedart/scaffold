<?php namespace Aedart\Scaffold\Collections;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties as TemplatePropertiesInterface;
use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;
use InvalidArgumentException;

/**
 * Template Properties Collection
 *
 * @see \Aedart\Scaffold\Contracts\Collections\TemplateProperties
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections
 */
class TemplateProperties implements TemplatePropertiesInterface
{
    use PartialCollectionTrait;

    /**
     * Template Properties Collection constructor.
     *
     * @param Property[] $properties [optional]
     */
    public function __construct(array $properties = [])
    {
        $this->populate($properties);
    }

    public function put($id, Property $property)
    {
        return ($this->getInternalCollection()->put($id, $property));
    }

    public function has($id)
    {
        return $this->getInternalCollection()->has($id);
    }

    public function get($id)
    {
        return $this->getInternalCollection()->get($id, null);
    }

    public function remove($id)
    {
        return ($this->getInternalCollection()->forget($id));
    }

    public function all()
    {
        return $this->getInternalCollection()->all();
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function populate(array $data = [])
    {
        $ioc = IoC::getInstance();

        foreach($data as $id => $property){
            if(is_string($id) && $property instanceof Property){
                $property->setId($id);
                $this->put($id, $property);
                continue;
            }

            if(is_string($id) && is_array($property)){
                $property['id'] = $id;

                /** @var Property $propertyObj */
                $propertyObj = $ioc->make(Property::class);
                $propertyObj->populate($property);

                $this->put($id, $propertyObj);
                continue;
            }

            throw new InvalidArgumentException(
                sprintf('Cannot populate Template Properties Collection with ["%s" => %s]', var_export($id, true), var_export($property, true))
            );
        }
    }
}