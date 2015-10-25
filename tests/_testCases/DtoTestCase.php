<?php
use Aedart\Testing\Laravel\TestCases\unit\UnitWithLaravelTestCase;

/**
 * Class DtoTestCase
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class DtoTestCase extends UnitWithLaravelTestCase{

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