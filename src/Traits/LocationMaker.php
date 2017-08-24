<?php namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation;

/**
 * Scaffold Location Maker
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait LocationMaker
{
    /**
     * Returns a new Scaffold Location object
     *
     * @param array $data [optional]
     *
     * @return ScaffoldLocation
     */
    public function makeLocation(array $data = [])
    {
        /** @var ScaffoldLocation $location */
        $location = IoC::getInstance()->make(ScaffoldLocation::class);
        $location->populate($data);

        return $location;
    }
}