<?php namespace Aedart\Scaffold\Data;

use Aedart\DTO\DataTransferObject;
use Aedart\Model\Traits\Strings\IdTrait;
use Aedart\Scaffold\Contracts\Data\AskableProperty as AskablePropertyInterface;
use Aedart\Scaffold\Contracts\Data\Template as TemplateInterface;
use Aedart\Scaffold\Traits\HandlerTrait;

/**
 * <h1>Template</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Data\Template
 *
 * @property string $id Identifier of this template - typically the template's filename
 * @property string $handler Class path to a given handler for this template
 * @property \Aedart\Scaffold\Contracts\Data\AskableProperty|null $filename This template's output filename
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Data
 */
class Template extends DataTransferObject implements TemplateInterface {

    use IdTrait, HandlerTrait;

    /**
     * The filename
     *
     * @var \Aedart\Scaffold\Contracts\Data\AskableProperty|null
     */
    protected $filename = null;

    public function setFilename(AskablePropertyInterface $property = null){
        $this->filename = $property;
    }

    public function getFilename(){
        return $this->filename;
    }

    public function toArray() {
        $arr = parent::toArray();

        // Make sure that property is also exported as an array
        $arr['filename'] = $this->getFilename()->toArray();

        return $arr;
    }
}