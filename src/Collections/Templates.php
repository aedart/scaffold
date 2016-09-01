<?php namespace Aedart\Scaffold\Collections;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\Templates as TemplatesInterface;
use Aedart\Scaffold\Contracts\Templates\Template;
use Aedart\Util\Traits\Collections\PartialCollectionTrait;
use InvalidArgumentException;

/**
 * Templates Collection
 *
 * @see \Aedart\Scaffold\Contracts\Collections\Templates
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections
 */
class Templates implements TemplatesInterface
{
    use PartialCollectionTrait;

    /**
     * Templates Collection constructor.
     *
     * @param Template[] $templates [optional]
     */
    public function __construct(array $templates = [])
    {
        $this->populate($templates);
    }

    public function put($id, Template $template)
    {
        return ($this->getInternalCollection()->put($id, $template));
    }

    public function has($id)
    {
        return $this->getInternalCollection()->has($id);
    }

    public function get($id)
    {
        return $this->getInternalCollection()->get($id);
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

        foreach($data as $id => $template){
            if(is_string($id) && $template instanceof Template){
                $template->setId($id);
                $this->put($id, $template);
                continue;
            }

            if(is_string($id) && is_array($template)){
                $template['id'] = $id;
                $this->put($id, $ioc->make(Template::class, $template));
                continue;
            }

            throw new InvalidArgumentException(
                sprintf('Cannot populate Templates Collection with ["%s" => %s]', var_export($id, true), var_export($template, true))
            );
        }
    }
}