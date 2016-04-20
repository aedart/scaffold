<?php

use Codeception\TestCase\Test;
use Faker\Factory;
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
}