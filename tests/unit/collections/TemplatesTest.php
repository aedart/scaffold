<?php

use Aedart\Scaffold\Collections\Templates;
use Aedart\Scaffold\Contracts\Templates\Template;

/**
 * Class TemplatesTest
 *
 * @group collections
 * @group templates
 *
 * @coversDefaultClass Aedart\Scaffold\Collections\Templates
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplatesTest extends BaseUnitTest
{
    /**
     * Returns a new Templates Collection instance
     *
     * @param Template[] $templates
     *
     * @return Templates
     */
    public function makeTemplatesCollection(array $templates = [])
    {
        return new Templates($templates);
    }

    /**
     * Returns a list of Templates (objects),
     * stated as array data
     *
     * @param int $amount [optional]
     *
     * @return string[]
     */
    public function makeArrayDataTemplatesList($amount = 3)
    {
        $output = [];

        while($amount--){
            $id = $this->faker->unique()->word;

            $output[$id] = [
                // We do not care about all properties of a template...
                'source' => $this->faker->randomDigit
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
        $collection = $this->makeTemplatesCollection();

        $this->assertNotNull($collection);
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     * @covers ::put
     */
    public function canPopulateTemplates()
    {
        $templates = $this->makeTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection($templates);

        $this->assertCount(count($templates), $collection, 'Did not add correct amount of templates');
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
        $templates = $this->makeArrayDataTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection($templates);

        $this->assertCount(count($templates), $collection, 'Did not add correct amount of templates');
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

        $this->makeTemplatesCollection($properties);
    }

    /**
     * @test
     *
     * @covers ::has
     * @covers ::offsetExists
     */
    public function hasTemplate()
    {
        $templates = $this->makeTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection($templates);

        $id = array_shift(array_keys($templates));

        $this->assertTrue(isset($collection[$id]), 'Template does not appear to exist in collection');
    }

    /**
     * @test
     *
     * @covers ::get
     * @covers ::offsetGet
     */
    public function canObtainTemplate()
    {
        $templates = $this->makeTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection($templates);

        $id = array_shift(array_keys($templates));

        $template = $collection[$id];

        $this->assertNotNull($template);
        $this->assertSame($templates[$id], $template, 'Incorrect template returned');
    }

    /**
     * @test
     *
     * @covers ::put
     * @covers ::get
     * @covers ::has
     * @covers ::offsetSet
     */
    public function canAddTemplateViaOffset()
    {
        $templates = $this->makeTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection();

        $id = array_shift(array_keys($templates));
        $template = $templates[$id];

        $collection[$id] = $template;

        $this->assertTrue($collection->has($id));
        $this->assertSame($template, $collection->get($id));
    }

    /**
     * @test
     *
     * @covers ::remove
     * @covers ::has
     * @covers ::offsetUnset
     */
    public function canRemoveTemplate()
    {
        $templates = $this->makeTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection($templates);

        $id = array_shift(array_keys($templates));

        unset($collection[$id]);

        $this->assertFalse($collection->has($id), 'Did not remove template from collection');
    }

    /**
     * @test
     *
     * @covers ::all
     */
    public function canObtainAllTemplates()
    {
        $templates = $this->makeTemplatesList(mt_rand(3, 5));
        $collection = $this->makeTemplatesCollection($templates);

        $this->assertSame($templates, $collection->all());
    }
}