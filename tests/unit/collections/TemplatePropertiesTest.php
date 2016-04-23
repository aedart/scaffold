<?php

use Aedart\Scaffold\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Mockery as m;

/**
 * Class TemplatePropertiesTest
 *
 * @group collections
 * @group templateProperties
 *
 * @coversDefaultClass Aedart\Scaffold\Collections\TemplateProperties
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplatePropertiesTest extends BaseUnitTest
{
    /**
     * Returns a new Template Data Properties Collection instance
     *
     * @param Property[] $properties
     *
     * @return TemplateProperties
     */
    public function makePropertiesCollection(array $properties = [])
    {
        return new TemplateProperties($properties);
    }

    /**
     * Returns a Template Data Property mock
     *
     * @return m\MockInterface|Property
     */
    public function makePropertyMock()
    {
        $prop = m::mock(Property::class);

        $id = $this->faker->unique()->word;

        $prop->shouldReceive('getId')
            ->andReturn($id);

        $prop->shouldReceive('setId')
            ->withAnyArgs();

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

    /**
     * Returns a list of Template Data properties,
     * stated as array data
     *
     * @param int $amount [optional]
     *
     * @return string[]
     */
    public function makeArrayDataPropertiesList($amount = 3)
    {
        $output = [];

        while($amount--){
            $id = $this->faker->unique()->word;

            $output[$id] = [
                // We do not care about all properties of the object,
                // just the type...
                'type' => $this->faker->randomDigit
            ];
        }

        return $output;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     */
    public function canObtainInstance()
    {
        $collection = $this->makePropertiesCollection();

        $this->assertNotNull($collection);
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     * @covers ::put
     */
    public function canPopulateProperties()
    {
        $properties = $this->makePropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection($properties);

        $this->assertCount(count($properties), $collection, 'Did not add correct amount of properties');
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     * @covers ::put
     */
    public function canPopulateWithArrayData()
    {
        $properties = $this->makeArrayDataPropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection($properties);

        $this->assertCount(count($properties), $collection, 'Did not add correct amount of properties');
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     *
     * @expectedException InvalidArgumentException
     */
    public function failsWhenIncorrectDataIsProvided()
    {
        $properties = $this->faker->words();

        $this->makePropertiesCollection($properties);
    }

    /**
     * @test
     *
     * @covers ::has
     * @covers ::offsetExists
     */
    public function hasProperty()
    {
        $properties = $this->makePropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection($properties);

        $id = array_shift(array_keys($properties));

        $this->assertTrue(isset($collection[$id]), 'Property does not appear to exist in collection');
    }

    /**
     * @test
     *
     * @covers ::get
     * @covers ::offsetGet
     */
    public function canObtainProperty()
    {
        $properties = $this->makePropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection($properties);

        $id = array_shift(array_keys($properties));

        $prop = $collection[$id];

        $this->assertNotNull($prop);
        $this->assertSame($properties[$id], $prop, 'Incorrect property returned');
    }

    /**
     * @test
     *
     * @covers ::put
     * @covers ::get
     * @covers ::has
     * @covers ::offsetSet
     */
    public function canAddPropertyViaOffset()
    {
        $properties = $this->makePropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection();

        $id = array_shift(array_keys($properties));
        $prop = $properties[$id];

        $collection[$id] = $prop;

        $this->assertTrue($collection->has($id));
        $this->assertSame($prop, $collection->get($id));
    }

    /**
     * @test
     *
     * @covers ::remove
     * @covers ::has
     * @covers ::offsetUnset
     */
    public function canRemoveProperty()
    {
        $properties = $this->makePropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection($properties);

        $id = array_shift(array_keys($properties));

        unset($collection[$id]);

        $this->assertFalse($collection->has($id), 'Did not remove property from collection');
    }

    /**
     * @test
     *
     * @covers ::all
     */
    public function canObtainAllProperties()
    {
        $properties = $this->makePropertiesList(mt_rand(3, 5));
        $collection = $this->makePropertiesCollection($properties);

        $this->assertSame($properties, $collection->all());
    }
}