<?php
namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Indexes\Index;

/**
 * Index Maker
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait IndexMaker
{

    /**
     * Returns a new index instance
     *
     * @param array $locations [optional]
     *
     * @return Index
     */
    public function makeIndex(array $locations = [])
    {
        return IoC::getInstance()->make(Index::class, $locations);
    }
}