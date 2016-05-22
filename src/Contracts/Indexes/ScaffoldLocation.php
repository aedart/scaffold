<?php namespace Aedart\Scaffold\Contracts\Indexes;

use Aedart\Model\Contracts\Strings\DescriptionAware;
use Aedart\Model\Contracts\Strings\FilePathAware;
use Aedart\Model\Contracts\Strings\NameAware;
use Aedart\Model\Contracts\Strings\PackageAware;
use Aedart\Model\Contracts\Strings\VendorAware;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Aedart\Util\Interfaces\Populatable;

/**
 * Scaffold Location
 *
 * Contains a file path to where a scaffold configuration file
 * is located, as well as a few other properties
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Indexes
 */
interface ScaffoldLocation extends VendorAware,
    PackageAware,
    NameAware,
    DescriptionAware,
    FilePathAware,
    ArrayAccess,
    Arrayable,
    Jsonable,
    JsonSerializable,
    Populatable
{

}