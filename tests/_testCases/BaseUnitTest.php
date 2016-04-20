<?php

use Codeception\TestCase\Test;
use Faker\Factory;
use Illuminate\Contracts\Logging\Log;
use Mockery as m;

/**
 * Base UnitTest
 *
 * The core test-class for all unit tests
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class BaseUnitTest extends Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Instance of the faker generator
     *
     * @var \Faker\Generator
     */
    protected $faker;

    protected function _before()
    {
        $this->faker = Factory::create();
    }

    protected function _after()
    {
        m::close();
    }

    /********************************************************
     * Helpers
     *******************************************************/

    /**
     * Returns instance of a log mock
     *
     * @return m\MockInterface|Log
     */
    public function makeLogMock()
    {
        return m::mock(Log::class);
    }
}