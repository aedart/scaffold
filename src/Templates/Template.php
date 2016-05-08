<?php namespace Aedart\Scaffold\Templates;

use Aedart\DTO\DataTransferObject;
use Aedart\Model\Traits\Strings\IdTrait;
use Aedart\Model\Traits\Strings\SourceTrait;
use Aedart\Scaffold\Contracts\Templates\Template as TemplateInterface;
use Aedart\Scaffold\Traits\Destination;

/**
 * Template
 *
 * @see \Aedart\Scaffold\Contracts\Templates\Template
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Templates
 */
class Template extends DataTransferObject implements TemplateInterface
{
    use IdTrait;
    use SourceTrait;
    use Destination;
}