<?php namespace Aedart\Scaffold\Templates\Data;

use Aedart\DTO\DataTransferObject;
use Aedart\Model\Traits\Arrays\ChoicesTrait;
use Aedart\Model\Traits\Integers\TypeTrait;
use Aedart\Model\Traits\Strings\IdTrait;
use Aedart\Model\Traits\Strings\QuestionTrait;
use Aedart\Model\Traits\Strings\ValueTrait;
use Aedart\Scaffold\Contracts\Templates\Data\Property as PropertyInterface;

/**
 * Template Data Property
 *
 * TODO: should adhere to an interface...
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Templates\Data
 */
class Property extends DataTransferObject implements PropertyInterface
{
    use IdTrait;
    use TypeTrait;
    use QuestionTrait;
    use ChoicesTrait;
    use ValueTrait;

    // TODO: validate trait
    // TODO: max Attempts trait
    // TODO: post Process trait
}