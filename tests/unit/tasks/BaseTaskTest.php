<?php

use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Tasks\BaseTask;
use Illuminate\Config\Repository;
use Mockery as m;
use Symfony\Component\Console\Output\OutputInterface;

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

    /**
     * Returns a new Dummy Task instance
     *
     * @param OutputInterface $output
     * @param Repository $config
     *
     * @return DummyTask
     */
    public function makeDummyTask(OutputInterface $output, Repository $config)
    {
        return new DummyTask($output, $config);
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

    /**
     * @test
     *
     * @covers ::resolveHandler
     *
     * NOTE: This test is an overlap of the IoCTest::canResolveAndConfigureAHandler !
     * We only test this here (again), because of (a) code coverage and (b) it is an
     * important method for many tasks
     */
    public function canResolveHandler()
    {
        $output = $this->makeOutputMock();
        $output->shouldReceive('text')
            ->once()
            ->with(m::type('string'));

        $config = new Repository([
            'handlers' => [
                'directory' => DirectoriesHandler::class
            ]
        ]);

        $task = $this->makeDummyTask($output, $config);

        $handler = $task->invokeResolveHandler('handlers.directory');

        $this->assertNotNull($handler);
    }
}

/**
 * Dummy Task
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DummyTask extends BaseTask
{

    public function __construct(OutputInterface $output, Repository $config)
    {
        $this->config = $config;
        $this->output = $output;
    }

    /**
     * Performs the actual task execution
     *
     * @return void
     */
    public function performTask()
    {
        // N/A
    }

    /**
     * Exposes the resolveHandler method, so that it
     * can be tested
     *
     * @param $alias
     *
     * @return \Aedart\Scaffold\Contracts\Handlers\Handler
     */
    public function invokeResolveHandler($alias)
    {
        return $this->resolveHandler($alias);
    }
}