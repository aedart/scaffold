<?php

use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Scaffold\Contracts\Templates\Data\Type;
use Mockery as m;

/**
 * Trait PropertyUtil
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
trait PropertyUtil
{
    /**
     * Returns a Template Data Property mock
     *
     * @param int $type [optional]
     *
     * @return Property|m\MockInterface
     */
    public function makePropertyMock($type = Type::VALUE)
    {
        $prop = m::mock(Property::class);

        $id = $this->faker->unique()->word;

        $prop->shouldReceive('getId')
            ->andReturn($id);

        $prop->shouldReceive('setId')
            ->withAnyArgs();

        $prop->shouldReceive('getType')
            ->andReturn($type);

        return $prop;
    }

    /**
     * Returns a list of mocked Template Data Properties
     *
     * @param int $amount [optional]
     *
     * @return Property[]
     */
    public function makePropertiesList($amount = 3)
    {
        $output = [];

        while($amount--){
            $property = $this->makePropertyMock();

            $output[$property->getId()] = $property;
        }

        return $output;
    }
}