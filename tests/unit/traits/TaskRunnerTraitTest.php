<?php

use Aedart\Scaffold\Traits\TaskRunner as TaskRunnerTrait;
use Mockery as m;

/**
 * Class TaskRunnerTraitTest
 *
 * @group traits
 * @group taskRunner
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TaskRunnerTraitTest extends TraitsTestCase
{

    use TaskRunnerUtils;

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     */
    public function runTaskRunnerTraitMethods(){
        $this->assertGetterSetterTraitMethods(TaskRunnerTrait::class, $this->makeConsoleTaskRunnerMock(), $this->makeConsoleTaskRunnerMock());
    }
}