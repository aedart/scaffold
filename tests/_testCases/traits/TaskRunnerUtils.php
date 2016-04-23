<?php

use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Mockery as m;

/**
 * Trait TaskRunnerUtils
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
trait TaskRunnerUtils
{

    /**
     * Returns a console task runner mock
     *
     * @return m\MockInterface|ConsoleTaskRunner
     */
    public function makeConsoleTaskRunnerMock()
    {
        return m::mock(ConsoleTaskRunner::class);
    }

}