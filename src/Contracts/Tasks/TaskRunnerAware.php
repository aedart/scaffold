<?php namespace Aedart\Scaffold\Contracts\Tasks;

/**
 * Task Runner Aware
 *
 * Component is aware of and able to retrieve a
 * console task runner instance
 *
 * @see \Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface TaskRunnerAware
{
    /**
     * Set the given task runner
     *
     * @param ConsoleTaskRunner $runner Instance of a Console Task Runner
     *
     * @return void
     */
    public function setTaskRunner(ConsoleTaskRunner $runner);

    /**
     * Get the given task runner
     *
     * If no task runner has been set, this method will
     * set and return a default task runner, if any such
     * value is available
     *
     * @see getDefaultTaskRunner()
     *
     * @return ConsoleTaskRunner|null task runner or null if none task runner has been set
     */
    public function getTaskRunner();

    /**
     * Get a default task runner value, if any is available
     *
     * @return ConsoleTaskRunner|null A default task runner value or Null if no default value is available
     */
    public function getDefaultTaskRunner();

    /**
     * Check if task runner has been set
     *
     * @return bool True if task runner has been set, false if not
     */
    public function hasTaskRunner();

    /**
     * Check if a default task runner is available or not
     *
     * @return bool True of a default task runner is available, false if not
     */
    public function hasDefaultTaskRunner();
}