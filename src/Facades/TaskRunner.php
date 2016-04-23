<?php namespace Aedart\Scaffold\Facades;

use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Illuminate\Support\Facades\Facade;

/**
 * Task Runner Facade
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Facades
 */
class TaskRunner extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return ConsoleTaskRunner::class;
    }
}