<?php

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Aedart\Scaffold\Contracts\Tasks\ConsoleTask;
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
     * Returns a console task mock
     *
     * @param bool $withName [optional]
     * @param bool $withDesc [optional]
     *
     * @return ConsoleTask|m\MockInterface
     */
    public function makeTaskMock($withName = true, $withDesc = true)
    {
        $task = m::mock(ConsoleTask::class);

        if($withName){
            $task->shouldReceive('getName')
                ->andReturn($this->faker->unique()->word);
        }

        if($withDesc){
            $task->shouldReceive('getDescription')
                ->andReturn($this->faker->unique()->sentence);
        }

        return $task;
    }

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