<?php namespace Aedart\Scaffold\Contracts\Tasks;

use Aedart\Scaffold\Exceptions\CannotExecuteTaskException;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console Task Runner
 *
 * Executes a set of console tasks
 *
 * @see \Aedart\Scaffold\Contracts\Tasks\ConsoleTask
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Tasks
 */
interface ConsoleTaskRunner
{
    /**
     * Execute the given console tasks
     *
     * @param string[]|ConsoleTask[] $tasks Class paths to the tasks that must be executed
     * @param InputInterface $input Console input
     * @param OutputInterface $output Console output
     * @param Repository $config Configuration to be passed to each task
     *
     * @return void
     *
     * @throws CannotExecuteTaskException
     */
    public function execute(array $tasks, InputInterface $input, OutputInterface $output, Repository $config);
}