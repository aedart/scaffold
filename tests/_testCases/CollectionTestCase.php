<?php
use Aedart\Testing\Laravel\TestCases\unit\UnitWithLaravelTestCase;

/**
 * Class CollectionTestCase
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class CollectionTestCase extends UnitWithLaravelTestCase{

    protected function getPackageProviders($app)
    {
        return [
            \Aedart\Scaffold\Providers\ScaffoldServiceProvider::class
        ];
    }

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

}