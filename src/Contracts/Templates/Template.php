<?php namespace Aedart\Scaffold\Contracts\Templates;

use Aedart\Model\Contracts\Strings\IdAware;
use Aedart\Model\Contracts\Strings\SourceAware;
use Aedart\Util\Interfaces\Populatable;

/**
 * Template
 *
 * This component represents some kind of a physical template.
 * It consists of a source location, a destination and an ID.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Templates
 */
interface Template extends IdAware,
    SourceAware,
    DestinationAware,
    Populatable
{

}