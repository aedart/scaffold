<?php

use Aedart\Scaffold\Tasks\BaseTask;
use Mockery as m;

/**
 * Class BaseTaskTest
 *
 * @group tasks
 * @group baseTask
 *
 * @coversDefaultClass Aedart\Scaffold\Tasks\BaseTask
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BaseTaskTest extends ConsoleTest
{

    /**
     * Returns a new Base Task instance
     *
     * @return m\Mock|BaseTask
     */
    public function makeBaseTask()
    {
        return m::mock(BaseTask::class)->makePartial();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::execute
     * @covers ::performTask
     */
    public function invokesPerformTask()
    {
        $task = $this->makeBaseTask();
        $task->shouldReceive('performTask')
            ->once();

        $task->execute($this->makeInputMock(), $this->makeOutputMock(), $this->makeConfigRepositoryMock());
    }
}