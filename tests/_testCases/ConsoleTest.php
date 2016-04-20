<?php

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mockery as m;

/**
 * Console Test
 *
 * Provides a few helpers for CLI / Console related
 * testing
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class ConsoleTest extends BaseUnitTest
{
    /********************************************************
     * Helpers
     *******************************************************/

    /**
     * Returns input mock
     *
     * @return m\MockInterface|InputInterface
     */
    public function makeInputMock()
    {
        return m::mock(InputInterface::class);
    }

    /**
     * Returns output mock
     *
     * @return m\MockInterface|OutputInterface
     */
    public function makeOutputMock()
    {
        return m::mock(OutputInterface::class);
    }
}