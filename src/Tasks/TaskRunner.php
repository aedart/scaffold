<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Contracts\Tasks\ConsoleTask;
use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Aedart\Scaffold\Exceptions\CannotExecuteTaskException;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Task Runner
 *
 * @see \Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class TaskRunner implements ConsoleTaskRunner
{
    public function execute(array $tasks, InputInterface $input, OutputInterface $output, Repository $config)
    {
        $i = 1;
        $total = count($tasks);

        foreach($tasks as $task){
            // Create new task instance
            $taskToExecute = $this->initTask($task);

            // Output status info
            $this->outputExecutionInfo($output, $taskToExecute, $i, $total);

            // Execute the task
            $taskToExecute->execute($input, $output, $config);
            $i++;
        }
    }

    /**
     * Init and return the console task
     *
     * @param string $task Class path of the task
     *
     * @return \Aedart\Scaffold\Contracts\Tasks\ConsoleTask
     *
     * @throws CannotExecuteTaskException
     */
    protected function initTask($task)
    {
        try {
            // Check if task is already initialised instance
            if($task instanceof ConsoleTask){
                return $task;
            }

            $consoleTask = (new $task);
            $this->assertIsConsoleTask($consoleTask);

            return $consoleTask;
        } catch (\Exception $e){
            throw new CannotExecuteTaskException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Assert that the given task is an instance of
     * "console task"
     *
     * @see \Aedart\Scaffold\Contracts\Tasks\ConsoleTask
     *
     * @param mixed $task
     *
     * @throws InvalidArgumentException
     */
    protected function assertIsConsoleTask($task)
    {
        if(!($task instanceof ConsoleTask)){
            throw new InvalidArgumentException(
                sprintf('Cannot execute task, %s is not instance of %s', get_class($task), ConsoleTask::class)
            );
        }
    }

    /**
     * Output execution status information to the console
     *
     * @param OutputInterface|StyleInterface $output
     * @param ConsoleTask $task
     * @param int $number The number of the task to be executed
     * @param int $total The total amount of tasks to be executed
     */
    protected function outputExecutionInfo($output, ConsoleTask $task, $number, $total)
    {
        $taskXofY = "Task ({$number}/{$total})";

        // Special formatting, if Symfony Style is provided
        if($output instanceof StyleInterface){
            $output->section($task->getName());
            $output->text($task->getDescription());
            $output->text($taskXofY);
            $output->newLine();
            return;
        }

        // Std. formatting
        $output->writeln($task->getName());
        $output->writeln('--------------------------------');
        $output->writeln($task->getDescription());
        $output->writeln($taskXofY);
        $output->writeln('');
    }
}