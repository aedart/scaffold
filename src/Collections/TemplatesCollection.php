<?php namespace Aedart\Scaffold\Collections;

use Aedart\Laravel\Helpers\Traits\Foundation\AppTrait;
use Aedart\Scaffold\Contracts\Collections\TemplatesCollection as TemplatesCollectionInterface;
use Aedart\Scaffold\Contracts\Data\Template;
use Aedart\Scaffold\Exceptions\InvalidIdException;
use Aedart\Scaffold\Exceptions\PopulateException;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;

/**
 * <h1>Templates Collection</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Collections\TemplatesCollection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections
 */
class TemplatesCollection implements TemplatesCollectionInterface {

    use PartialCollectionTrait, AppTrait;

    /**
     * Create a new instance of this collection
     *
     * @param Template[]|array[] $properties [optional]
     */
    public function __construct(array $properties = []) {
        $this->populate($properties);
    }

    public function add(Template $template){
        if(!$template->hasId()){
            throw new InvalidIdException('Template has no id set, cannot add to collection');
        }

        $this->getInternalCollection()->put($template->getId(), $template);
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
     * @param array|Template $template
     *
     * @throws PopulateException
     */
    protected function insert($template){
        if($template instanceof Template){
            $this->add($template);
            return;
        }

        if(is_array($template)){
            $dto = $this->getApp()->make(Template::class, $template);
            $this->add($dto);
            return;
        }

        throw new PopulateException(
            sprintf(
                'Cannot populate %s, given entry (%s) must either be instance of %s or a valid array',
                get_class($this),
                var_export($template, true),
                Template::class
            )
        );
    }
}