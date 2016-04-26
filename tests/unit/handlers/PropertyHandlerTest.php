<?php

use Aedart\Scaffold\Contracts\Templates\Data\Property;
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
class PropertyHandlerTest extends ConsoleTest
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
     * Custom assertions
     *******************************************************/

    /**
     * Assert that the handler is able to obtain a value
     * for the given property's type
     *
     * @param Property $property
     * @param StyleInterface $output
     * @param $expectedValue
     * @param string $failMsg [optional]
     */
    public function assertCanObtainValueFor(
        Property $property,
        StyleInterface $output,
        $expectedValue,
        $failMsg = 'Incorrect value obtained'
    ) {
        $key = $this->faker->word;

        $config = $this->makeConfigRepositoryMock();

        $handler = $this->makePropertyHandler($config, $key, $output);

        $result = $handler->obtainValueFor($property);

        $this->assertSame($expectedValue, $result, $failMsg);
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

    /**
     * @test
     *
     * @covers ::obtainValueFor
     * @covers ::handleValueType
     */
    public function canObtainValueForTypeValue()
    {
        $output = $this->makeStyleInterfaceMock();

        $value = $this->faker->uuid;
        $property = $this->makePropertyMock(Type::VALUE);
        $property->shouldReceive('getValue')
            ->andReturn($value);

        $this->assertCanObtainValueFor($property, $output, $value, 'Cannot obtain value for type: Value');
    }

    /**
     * @test
     *
     * @covers ::obtainValueFor
     * @covers ::handleQuestionType
     */
    public function canObtainValueForTypeQuestion()
    {
        $value = $this->faker->uuid;
        $processedValue = $this->faker->uuid;

        $question = $this->faker->sentence;

        $validation = function($answer){return $answer;};

        $property = $this->makePropertyMock(Type::QUESTION);
        $property->shouldReceive('getValue')
            ->andReturn($value);
        $property->shouldReceive('getQuestion')
            ->andReturn($question);
        $property->shouldReceive('getValidation')
            ->andReturn($validation);

        $output = $this->makeStyleInterfaceMock();
        $output->shouldReceive('ask')
            ->with($question, $value, $validation)
            ->andReturn($processedValue);

        $this->assertCanObtainValueFor($property, $output, $processedValue, 'Cannot obtain value for type: Question');
    }

    /**
     * @test
     *
     * @covers ::obtainValueFor
     * @covers ::handleChoiceType
     */
    public function canObtainValueForTypeChoice()
    {
        $value = $this->faker->uuid;
        $processedValue = $this->faker->uuid;

        $question = $this->faker->sentence;

        $choices = [
            $this->faker->sentence,
            $this->faker->sentence,
            $this->faker->sentence,
        ];

        $property = $this->makePropertyMock(Type::CHOICE);
        $property->shouldReceive('getValue')
            ->andReturn($value);
        $property->shouldReceive('getQuestion')
            ->andReturn($question);
        $property->shouldReceive('getChoices')
            ->andReturn($choices);

        $output = $this->makeStyleInterfaceMock();
        $output->shouldReceive('choice')
            ->with($question, $choices, $value)
            ->andReturn($processedValue);

        $this->assertCanObtainValueFor($property, $output, $processedValue, 'Cannot obtain value for type: Choice');
    }

    /**
     * @test
     *
     * @covers ::obtainValueFor
     * @covers ::handleConfirmType
     */
    public function canObtainValueForTypeConfirm()
    {
        $value = $this->faker->uuid;
        $processedValue = $this->faker->uuid;

        $question = $this->faker->sentence;

        $property = $this->makePropertyMock(Type::CONFIRM);
        $property->shouldReceive('getValue')
            ->andReturn($value);
        $property->shouldReceive('getQuestion')
            ->andReturn($question);

        $output = $this->makeStyleInterfaceMock();
        $output->shouldReceive('confirm')
            ->with($question, $value)
            ->andReturn($processedValue);

        $this->assertCanObtainValueFor($property, $output, $processedValue, 'Cannot obtain value for type: Confirm');
    }

//    /**
//     * @test
//     *
//     * @covers ::obtainValueFor
//     * @covers ::handleHiddenType
//     */
//    public function canObtainValueForTypeHidden()
//    {
//        $value = $this->faker->uuid;
//        $processedValue = $this->faker->uuid;
//
//        $question = $this->faker->sentence;
//
//        $property = $this->makePropertyMock(Type::HIDDEN);
//        $property->shouldReceive('getValue')
//            ->andReturn($value);
//        $property->shouldReceive('getQuestion')
//            ->andReturn($question);
//
//        $output = $this->makeStyleInterfaceMock();
//        $output->shouldReceive('askHidden')
//            ->with($question, $value)
//            ->andReturn($processedValue);
//
//        $this->assertCanObtainValueFor($property, $output, $processedValue, 'Cannot obtain value for type: Hidden');
//    }

    /**
     * @test
     *
     * @covers ::obtainValueFor
     * @covers ::handleUnknownType
     */
    public function canObtainValueForTypeUnknown()
    {
        $value = $this->faker->uuid;

        $property = $this->makePropertyMock(null);
        $property->shouldReceive('getValue')
            ->andReturn($value);

        $output = $this->makeStyleInterfaceMock();

        $output->shouldReceive('warning')
            ->with(m::type('string'));

        $this->assertCanObtainValueFor($property, $output, $value, 'Cannot obtain value for type: unknown');
    }
}