<?php

use Aedart\Scaffold\Handlers\BaseHandler;
use Mockery as m;

/**
 * Class BaseHandlerTest
 *
 * @group handlers
 * @group baseHandler
 *
 * @coversDefaultClass Aedart\Scaffold\Handlers\BaseHandler
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BaseHandlerTest extends BaseUnitTest
{
    /**
     * Returns instance of the base handler
     *
     * @return m\Mock|BaseHandler
     */
    public function makeBaseHandlerMock()
    {
        $mock = m::mock(BaseHandler::class)->makePartial();

        return $mock;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::process
     * @covers ::processElement
     */
    public function canInvokeProcessElement()
    {
        $handler = $this->makeBaseHandlerMock();

        $element = $this->faker->word;

        $handler->shouldReceive('processElement')
            ->once()
            ->with($element);

        $handler->process($element);
    }

    /**
     * @test
     *
     * @covers ::process
     * @covers ::processElement
     *
     * @expectedException \Aedart\Scaffold\Exceptions\UnableToProcessElementException
     */
    public function canHandleError()
    {

        $handler = $this->makeBaseHandlerMock();

        $element = $this->faker->word;

        $handler->shouldReceive('processElement')
            ->once()
            ->with($element)
            ->andThrow(\Exception::class);

        $handler->process($element);
    }
}