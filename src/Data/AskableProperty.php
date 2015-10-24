<?php namespace Aedart\Scaffold\Data;

use Aedart\DTO\DataTransferObject;
use Aedart\Model\Traits\Booleans\AskableTrait;
use Aedart\Model\Traits\Strings\DescriptionTrait;
use Aedart\Model\Traits\Strings\IdTrait;
use Aedart\Scaffold\Contracts\Data\AskableProperty as AskablePropertyInterface;

/**
 * <h1>Askable Property</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Data\AskableProperty
 *
 * @property string $id Identifier of this askable-property
 * @property bool $ask State that indicates if a default value should be asked for
 * @property string $description A description of the given value or default value that this DTO contains
 * @property mixed|null $default [optional] The default value of this DTO
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Data
 */
class AskableProperty extends DataTransferObject implements AskablePropertyInterface {

    use IdTrait, AskableTrait, DescriptionTrait;

    /**
     * A default value to be used
     *
     * @var mixed|null
     */
    protected $default = null;

    public function setDefault($value = null){
        $this->default = $value;
    }

    public function getDefault() {
        return $this->default;
    }

    public function hasDefault() {
        return !is_null($this->default);
    }

    public function getDefaultAsk() {
        return false;
    }
}