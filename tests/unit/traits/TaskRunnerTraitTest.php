<?php

use Aedart\Scaffold\Traits\TaskRunner as TaskRunnerTrait;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use Mockery as m;

/**
 * Class TaskRunnerTraitTest
 *
 * @group traits
 * @group taskRunner
 *
 * @coversDefaultClass Aedart\Scaffold\Traits\TaskRunner
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TaskRunnerTraitTest extends GetterSetterTraitTestCase
{

    use TaskRunnerUtils;

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath()
    {
        return TaskRunnerTrait::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName()
    {
        return 'taskRunner';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setTaskRunner
     * @covers ::getTaskRunner
     * @covers ::hasTaskRunner
     * @covers ::hasDefaultTaskRunner
     * @covers ::getDefaultTaskRunner
     */
    public function taskRunnerTraitMethods(){
        $this->assertGetterSetterTraitMethods($this->makeConsoleTaskRunnerMock(), null);
    }
}