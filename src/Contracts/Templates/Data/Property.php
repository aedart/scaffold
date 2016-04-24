<?php namespace Aedart\Scaffold\Contracts\Templates\Data;

use Aedart\Model\Contracts\Arrays\ChoicesAware;
use Aedart\Model\Contracts\Integers\IdAware;
use Aedart\Model\Contracts\Integers\TypeAware;
use Aedart\Model\Contracts\Strings\QuestionAware;
use Aedart\Model\Contracts\Strings\ValueAware;
use Aedart\Util\Interfaces\Populatable;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Template Data Property
 *
 * A data object that contains the final
 * value which can be used inside a template and or
 * meta information on how to obtain the value.
 *
 * Each property has a type, which can be used to
 * determine how the value must be obtained.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Templates\Data
 */
interface Property extends IdAware,
    TypeAware,
    QuestionAware,
    ChoicesAware,
    ValueAware,
    ValidationAware,
    PostProcessAware,
    ArrayAccess,
    Arrayable,
    Jsonable,
    JsonSerializable,
    Populatable
{

}