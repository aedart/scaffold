<?php

use Aedart\Scaffold\Contracts\Tasks\ConsoleTask;
use Aedart\Scaffold\Handlers\FilesHandler;
use Aedart\Scaffold\Tasks\TaskRunner;
use Mockery as m;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Class TaskRunnerTest
 *
 * @group tasks
 * @group taskRunner
 *
 * @coversDefaultClass Aedart\Scaffold\Tasks\TaskRunner
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TaskRunnerTest extends ConsoleTest
{

    /**
     * Returns a new instance of the task runner
     *
     * @return TaskRunner
     */
    public function makeTaskRunner()
    {
        return new TaskRunner();
    }

    /**
     * Returns a list of mocked tasks
     *
     * @param int $amount [optional]
     * @param bool $expectToExecute [optional]
     *
     * @return ConsoleTask[]|m\MockInterface[]
     */
    public function makeTaskList($amount = 3, $expectToExecute = false)
    {
        $output = [];

        while($amount--){
            $task = $this->makeTaskMock();

            if($expectToExecute){
                $task->shouldReceive('execute')
                    ->once()
                    ->withAnyArgs();
            }

            $output[] = $task;
        }

        return $output;
    }

    /**
     * Returns a console output / symfony style mock
     *
     * @return m\MockInterface|OutputInterface|StyleInterface
     */
    public function makeSymfonyStyleMock()
    {
        return m::mock(OutputInterface::class, StyleInterface::class);
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::execute
     *
     * @covers ::initTask
     * @covers ::outputExecutionInfo
     */
    public function canExecuteTasks()
    {
        $tasks = $this->makeTaskList(mt_rand(3, 5), true);

        $input = $this->makeInputMock();

        $output = $this->makeOutputMock();
        $output->shouldReceive('writeln')
            ->withAnyArgs();

        $config = $this->makeConfigRepositoryMock();

        $runner = $this->makeTaskRunner();

        $runner->execute($tasks, $input, $output, $config);
    }

    /**
     * @test
     *
     * @covers ::execute
     *
     * @covers ::initTask
     * @covers ::assertIsConsoleTask
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotExecuteTaskException
     */
    public function failsWhenTaskIsNotAConsoleTask()
    {
        // NOTE here the tasks are invalid !
        $tasks = [
            FilesHandler::class
        ];

        $input = $this->makeInputMock();

        $output = $this->makeOutputMock();
        $output->shouldReceive('writeln')
            ->withAnyArgs();

        $config = $this->makeConfigRepositoryMock();

        $runner = $this->makeTaskRunner();

        $runner->execute($tasks, $input, $output, $config);
    }

    /**
     * @test
     *
     * NOTE: This test is JUST and only for coverage - to ensure
     * that special output is delivered in case of StyleInterface
     * being used - THIS MIGHT CHANGE IN THE FUTURE!
     *
     * @covers ::execute
     *
     * @covers ::initTask
     * @covers ::outputExecutionInfo
     */
    public function canOutputViaSymfonyStyle()
    {
        $tasks = $this->makeTaskList(mt_rand(3, 5), true);

        $input = $this->makeInputMock();

        $output = $this->makeSymfonyStyleMock();
        $output->shouldReceive('section')
            ->withAnyArgs();
        $output->shouldReceive('text')
            ->withAnyArgs();
        $output->shouldReceive('newLine')
            ->withAnyArgs();

        $config = $this->makeConfigRepositoryMock();

        $runner = $this->makeTaskRunner();

        $runner->execute($tasks, $input, $output, $config);
    }
}