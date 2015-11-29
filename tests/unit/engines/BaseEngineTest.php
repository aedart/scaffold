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
     * Get the source location of where the templates are
     * located
     *
     * @return string Relative path inside the '_data/handlers/templates/'
     */
    public function templatesLocation(){
        return '';
    }

    /**
     * Get the output location of where rendered templates
     * must be written to
     *
     * @return string Relative path inside the '_output/handlers/templates/'
     */
    public function templatesOutputLocation(){
        return '';
    }

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