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
 * TODO: Desc...
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Templates\Data
 */
interface Property extends IdAware,
    TypeAware,
    QuestionAware,
    ChoicesAware,
    ValueAware,
    // TODO: validate aware
    PostProcessAware,

    ArrayAccess,
    Arrayable,
    Jsonable,
    JsonSerializable,
    Populatable
{

}