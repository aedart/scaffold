<?php

use Aedart\Scaffold\Collections\AskablePropertiesCollection;
use Aedart\Scaffold\Data\AskableProperty;
use \Mockery as m;

/**
 * Class AskablePropertiesCollectionTest
 *
 * @group collections
 * @coversDefaultClass Aedart\Scaffold\Collections\AskablePropertiesCollection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class AskablePropertiesCollectionTest extends CollectionTestCase{

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Make a new collection
     *
     * @param \Aedart\Scaffold\Contracts\Data\AskableProperty[]|array[] $elements [optional]
     *
     * @return AskablePropertiesCollection
     */
    public function makeCollection(array $elements = []){
        return new AskablePropertiesCollection($elements);
    }

    /**
     * Get a askable-property mock
     *
     * @return m\MockInterface|AskableProperty
     */
    public function getAskablePropertyMock() {
        $id = $this->faker->uuid;

        $mock = m::mock(AskableProperty::class)->makePartial();
        //        $mock->shouldReceive('hasId')
        //            ->andReturn(true);
        //        $mock->shouldReceive('getId')
        //            ->andReturn($id);
        $mock->setId($id);

        return $mock;
    }

    /**
     * Get an askable-property as an array
     *
     * @return array
     */
    public function getAskablePropertyAsArray() {
        return [
            'id'            => $this->faker->uuid,
            'ask'           => $this->faker->boolean(),
            'description'   => $this->faker->sentence,
            'default'       => $this->faker->word
        ];
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::add
     * @covers ::get
     * @covers ::has
     */
    public function canAddAndRetrieveAProperty() {
        $collection = $this->makeCollection();

        $property = $this->getAskablePropertyMock();
        $id = $property->getId();

        $collection->add($property);

        $this->assertTrue($collection->has($id), 'Collection should contain a property');
        $this->assertSame($property, $collection->get($id), 'Incorrect property returned');
        $this->assertCount(1, $collection, 'Incorrect amount of properties added');
    }

    /**
     * @test
     * @covers ::add
     *
     * @expectedException \Aedart\Scaffold\Exceptions\InvalidIdException
     */
    public function failsAddingPropertyWithoutId() {
        $collection = $this->makeCollection();

        $property = $this->getAskablePropertyMock();
        $property->shouldReceive('hasId')
            ->andReturn(false);

        $collection->add($property);
    }

    /**
     * @test
     * @covers ::remove
     */
    public function canRemoveAProperty() {
        $collection = $this->makeCollection();

        $property = $this->getAskablePropertyMock();
        $id = $property->getId();

        $collection->add($property);

        $collection->remove($id);

        $this->assertFalse($collection->has($id), 'Should not contain any properties');
        $this->assertCount(0, $collection, 'Incorrect amount of properties removed');
    }

    /**
     * @test
     * @covers ::remove
     */
    public function attemptToRemoveNoneExistingProperty() {
        $collection = $this->makeCollection();

        $property = $this->getAskablePropertyMock();

        $collection->add($property);

        $collection->remove($this->faker->uuid);

        $this->assertCount(1, $collection, 'Should not have removed any properties');
    }

    /**
     * @test
     * @covers ::offsetExists
     * @covers ::offsetGet
     * @covers ::offsetSet
     * @covers ::insert
     */
    public function canAddAndRetrieveViaOffset() {
        $collection = $this->makeCollection();

        $property = $this->getAskablePropertyMock();
        $id = $property->getId();

        $collection[$id] = $property;

        $this->assertTrue(isset($collection[$id]), 'Should contain property');
        $this->assertSame($property, $collection[$id], 'Incorrect property returned');
        $this->assertCount(1, $collection, 'Incorrect amount of properties added');
    }

    /**
     * @test
     * @covers ::offsetUnset
     */
    public function canRemoveViaOffset() {
        $collection = $this->makeCollection();

        $property = $this->getAskablePropertyMock();
        $id = $property->getId();

        $collection[$id] = $property;

        unset($collection[$id]);

        $this->assertFalse(isset($collection[$id]), 'Should NOT contain property');
        $this->assertCount(0, $collection, 'Incorrect amount of properties removed');
    }

    /**
     * @test
     * @covers ::populate
     */
    public function doesNotAttemptToPopulateWhenNoElementsProvided() {
        $collection = $this->makeCollection([]);

        $this->assertTrue($collection->isEmpty(), 'Should be empty');
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::populate
     * @covers ::insert
     */
    public function canPopulateWithPropertyInstances() {
        $elements = [
            $this->getAskablePropertyMock(),
            $this->getAskablePropertyMock(),
            $this->getAskablePropertyMock(),
            $this->getAskablePropertyMock(),
            $this->getAskablePropertyMock(),
        ];

        $collection = $this->makeCollection($elements);

        $this->assertSame(count($elements), $collection->count(), 'Incorrect amount of elements added');
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::populate
     * @covers ::insert
     *
     * @covers Aedart\Scaffold\Providers\ScaffoldServiceProvider::registerAskableProperty
     */
    public function canPopulateWithArrays() {
        $elements = [
            $this->getAskablePropertyAsArray(),
            $this->getAskablePropertyAsArray(),
            $this->getAskablePropertyAsArray(),
            $this->getAskablePropertyAsArray(),
        ];

        $collection = $this->makeCollection($elements);

        $this->assertSame(count($elements), $collection->count(), 'Incorrect amount of elements added');
    }

    /**
     * @test
     * @covers ::populate
     * @covers ::insert
     *
     * @expectedException \Aedart\Scaffold\Exceptions\PopulateException
     */
    public function failsWhenAttemptingToPopulateWithDifferentData() {
        $elements = [
            true,
            false,
            $this->faker->sentence,
        ];

        $collection = $this->makeCollection($elements);
    }
}