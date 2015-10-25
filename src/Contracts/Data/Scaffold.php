<?php namespace Aedart\Scaffold\Contracts\Data;

use Aedart\DTO\Contracts\DataTransferObject;
use Aedart\Model\Contracts\Strings\BasePathAware;
use Aedart\Model\Contracts\Strings\DescriptionAware;
use Aedart\Model\Contracts\Strings\NameAware;
use Aedart\Scaffold\Contracts\DataCollectionAware;
use Aedart\Scaffold\Contracts\DirectoryHandlerAware;
use Aedart\Scaffold\Contracts\TemplatesCollectionAware;

/**
 * <h1>Scaffold</h1>
 *
 * In this context, a scaffold is a representation of a given
 * "scaffold" project. It contains information about its name,
 * description, how to handle directories, templates and what
 * data those templates require.
 *
 * <br />
 *
 * If a given "scaffold" configuration file is loaded correctly,
 * it is used to populate an instance of this DTO. Furthermore,
 * this instance can then be passed to those internal components,
 * that depend upon the scaffold's information.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Data
 */
interface Scaffold extends DataTransferObject,
    NameAware,
    DescriptionAware,
    BasePathAware,
    DirectoryHandlerAware,
    TemplatesCollectionAware,
    DataCollectionAware
{

}