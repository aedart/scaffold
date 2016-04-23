<?php namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Aedart\Scaffold\Contracts\Tasks\TaskRunnerAware;
use Aedart\Scaffold\Facades\TaskRunner as TaskRunnerFacade;

/**
 * Task Runner Trait
 *
 * @see Aedart\Scaffold\Contracts\Tasks\TaskRunnerAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait TaskRunner
{
    /**
     * Instance of a Console Task Runner
     *
     * @var ConsoleTaskRunner|null
     */
    protected $taskRunner = null;

    /**
     * Set the given task runner
     *
     * @param ConsoleTaskRunner $runner Instance of a Console Task Runner
     *
     * @return void
     */
    public function setTaskRunner(ConsoleTaskRunner $runner)
    {
        $this->taskRunner = $runner;
    }

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
    public function getTaskRunner()
    {
        if (!$this->hasTaskRunner() && $this->hasDefaultTaskRunner()) {
            $this->setTaskRunner($this->getDefaultTaskRunner());
        }
        return $this->taskRunner;
    }

    /**
     * Get a default task runner value, if any is available
     *
     * @return ConsoleTaskRunner|null A default task runner value or Null if no default value is available
     */
    public function getDefaultTaskRunner()
    {
        return TaskRunnerFacade::getFacadeRoot();
    }

    /**
     * Check if task runner has been set
     *
     * @return bool True if task runner has been set, false if not
     */
    public function hasTaskRunner()
    {
        return !is_null($this->taskRunner);
    }

    /**
     * Check if a default task runner is available or not
     *
     * @return bool True of a default task runner is available, false if not
     */
    public function hasDefaultTaskRunner()
    {
        return !is_null($this->getDefaultTaskRunner());
    }
}