<?php

use Aedart\Scaffold\Contracts\Templates\Data\Type;
use Aedart\Scaffold\Handlers\PropertyHandler;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Symfony\Component\Console\Style\StyleInterface;
use Mockery as m;

/**
 * Class PropertyHandlerTest
 *
 * @group handlers
 * @group propertyHandler
 *
 * @coversDefaultClass Aedart\Scaffold\Handlers\PropertyHandler
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class PropertyHandlerTest extends BaseUnitTest
{
    /**
     * Returns a new Property handler instance
     *
     * @param Repository $config
     * @param $key
     * @param StyleInterface $output
     * @param Log|null $log [optional]
     *
     * @return PropertyHandler
     */
    public function makePropertyHandler(Repository $config, $key, StyleInterface $output, Log $log = null)
    {

        $handler = new PropertyHandler($config, $key, $output);

        $handler->setOutputPath($this->outputPath());
        $handler->setFile($this->getFilesystem());

        if(!is_null($log)){
            $handler->setLog($log);
        }

        return $handler;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     */
    public function canObtainInstance()
    {
        $key = $this->faker->word;

        $config = $this->makeConfigRepositoryMock();

        $output = $this->makeStyleInterfaceMock();

        $handler = $this->makePropertyHandler($config, $key, $output);

        $this->assertNotNull($handler);
    }

    /**
     * @test
     *
     * NOTE: In this text we do not test individual
     * property types - we are just testing the general
     * work flow
     *
     * @covers ::processProperty
     *
     * @covers ::processElement
     * @covers ::obtainValueFor
     * @covers ::applyPostProcessOn
     * @covers ::saveValueFor
     */
    public function canProcessTemplateDataProperty()
    {
        $value = $this->faker->uuid;

        $key = $this->faker->word;

        $config = $this->makeConfigRepositoryMock();
        $config->shouldReceive('set')
            ->with($key, $value);

        $output = $this->makeStyleInterfaceMock();
        $output->shouldReceive('text')
            ->with(m::type('string'));

        $property = $this->makePropertyMock(Type::VALUE);
        $property->shouldReceive('getValue')
            ->andReturn($value);
        $property->shouldReceive('hasPostProcess')
            ->andReturn(false);
        $property->shouldReceive('hasDefaultPostProcess')
            ->andReturn(false);

        $handler = $this->makePropertyHandler($config, $key, $output);

        $handler->processProperty($property);
    }
}