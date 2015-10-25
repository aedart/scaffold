<?php

use Aedart\Scaffold\Collections\TemplatesCollection;
use Aedart\Scaffold\Data\Template;
use \Mockery as m;

/**
 * Class TemplatesCollectionTest
 *
 * @group collections
 * @coversDefaultClass Aedart\Scaffold\Collections\TemplatesCollection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplatesCollectionTest extends CollectionTestCase{

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Make a new collection
     *
     * @param \Aedart\Scaffold\Contracts\Data\Template[]|array[] $elements [optional]
     *
     * @return TemplatesCollection
     */
    public function makeCollection(array $elements = []){
        return new TemplatesCollection($elements);
    }

    /**
     * Get a template mock
     *
     * @return m\MockInterface|Template
     */
    public function getTemplateMock() {
        $id = $this->faker->uuid;

        $mock = m::mock(Template::class)->makePartial();
        $mock->setId($id);

        return $mock;
    }

    /**
     * Get an askable-property as an array
     *
     * @return array
     */
    public function getTemplateAsArray() {
        return [
            'id'            => $this->faker->uuid,
            'handler'       => $this->faker->word . '/' . $this->faker->word,
            'filename'      => [
                'id'            => $this->faker->uuid,
                'ask'           => $this->faker->boolean(),
                'description'   => $this->faker->sentence,
                'default'       => $this->faker->word
            ]
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
    public function canAddAndRetrieveATemplate() {
        $collection = $this->makeCollection();

        $template = $this->getTemplateMock();
        $id = $template->getId();

        $collection->add($template);

        $this->assertTrue($collection->has($id), 'Collection should contain a template');
        $this->assertSame($template, $collection->get($id), 'Incorrect template returned');
        $this->assertCount(1, $collection, 'Incorrect amount of templates added');
    }

    /**
     * @test
     * @covers ::add
     *
     * @expectedException \Aedart\Scaffold\Exceptions\InvalidIdException
     */
    public function failsAddingTemplateWithoutId() {
        $collection = $this->makeCollection();

        $template = $this->getTemplateMock();
        $template->shouldReceive('hasId')
            ->andReturn(false);

        $collection->add($template);
    }

    /**
     * @test
     * @covers ::remove
     */
    public function canRemoveATemplate() {
        $collection = $this->makeCollection();

        $template = $this->getTemplateMock();
        $id = $template->getId();

        $collection->add($template);

        $collection->remove($id);

        $this->assertFalse($collection->has($id), 'Should not contain any templates');
        $this->assertCount(0, $collection, 'Incorrect amount of templates removed');
    }

    /**
     * @test
     * @covers ::remove
     */
    public function attemptToRemoveNoneExistingTemplate() {
        $collection = $this->makeCollection();

        $template = $this->getTemplateMock();

        $collection->add($template);

        $collection->remove($this->faker->uuid);

        $this->assertCount(1, $collection, 'Should not have removed any templates');
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

        $template = $this->getTemplateMock();
        $id = $template->getId();

        $collection[$id] = $template;

        $this->assertTrue(isset($collection[$id]), 'Should contain template');
        $this->assertSame($template, $collection[$id], 'Incorrect template returned');
        $this->assertCount(1, $collection, 'Incorrect amount of templates added');
    }

    /**
     * @test
     * @covers ::offsetUnset
     */
    public function canRemoveViaOffset() {
        $collection = $this->makeCollection();

        $template = $this->getTemplateMock();
        $id = $template->getId();

        $collection[$id] = $template;

        unset($collection[$id]);

        $this->assertFalse(isset($collection[$id]), 'Should NOT contain template');
        $this->assertCount(0, $collection, 'Incorrect amount of templates removed');
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
            $this->getTemplateMock(),
            $this->getTemplateMock(),
            $this->getTemplateMock(),
            $this->getTemplateMock(),
            $this->getTemplateMock(),
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
     * @covers Aedart\Scaffold\Providers\ScaffoldServiceProvider::registerTemplate
     */
    public function canPopulateWithArrays() {
        $elements = [
            $this->getTemplateAsArray(),
            $this->getTemplateAsArray(),
            $this->getTemplateAsArray(),
            $this->getTemplateAsArray(),
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