<?php

use Aedart\Scaffold\Handlers\BaseHandler;
use \Mockery as m;

/**
 * Class BaseHandlerTest
 *
 * @group handlers
 * @coversDefaultClass Aedart\Scaffold\Handlers\BaseHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BaseHandlerTest extends HandlerTestCase{

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Get the handler in question
     *
     * @return m\Mock|BaseHandler
     */
    public function getBaseHandler() {
        return m::mock(BaseHandler::class)->makePartial();
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     *
     * @expectedException \Aedart\Scaffold\Exceptions\UnableToHandleElementException
     */
    public function throwsExceptionWhenUnableToProcessElement(){
        $handler = $this->getBaseHandler();

        $handler->shouldReceive('processElement')
            ->withAnyArgs()
            ->andThrow(Exception::class, 'Testing try catch....');

        $handler->handle($this->faker->word);
    }
}