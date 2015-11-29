<?php

use Aedart\Scaffold\Engines\BaseEngine;
use \Mockery as m;

/**
 * Class BaseEngineTest
 *
 * @group engines
 * @group base-engine
 * @coversDefaultClass Aedart\Scaffold\Engines\BaseEngine
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BaseEngineTest extends EngineTestCase{

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Get the base-engine instance
     *
     * @return m\Mock|BaseEngine
     */
    public function getBaseEngine() {
        return m::mock(BaseEngine::class)->makePartial();
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::render
     */
    public function triggersDoRender() {
        $engine = $this->getBaseEngine();
        $engine->shouldReceive('doRender')
            ->once();

        $engine->render();
    }

    /**
     * @test
     * @covers ::render
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotRenderTemplateException
     */
    public function failsWithCustomExceptionOnRenderingFailure() {
        $engine = $this->getBaseEngine();

        $engine->shouldReceive('doRender')
            ->andThrow(Exception::class, 'Render failure');

        $engine->render();
    }
}