<?php namespace Aedart\Scaffold\Indexes;

use Aedart\Model\Traits\Strings\DescriptionTrait;
use Aedart\Model\Traits\Strings\FilePathTrait;
use Aedart\Model\Traits\Strings\NameTrait;
use Aedart\Model\Traits\Strings\PackageTrait;
use Aedart\Model\Traits\Strings\VendorTrait;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation as ScaffoldLocationInterface;

/**
 * Scaffold Location
 *
 * @see \Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Indexes
 */
class ScaffoldLocation implements ScaffoldLocationInterface
{
    use VendorTrait;
    use PackageTrait;
    use NameTrait;
    use DescriptionTrait;
    use FilePathTrait;
}