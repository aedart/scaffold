<?php

use Aedart\Scaffold\Contracts\Templates\Data\Type;
use Aedart\Scaffold\Templates\Data\Property;

/**
 * Class PropertyTest
 *
 * @group templates
 * @group templatesData
 * @group property
 *
 * @coversDefaultClass Aedart\Scaffold\Templates\Data\Property
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class PropertyTest extends BaseUnitTest
{

    /**
     * Returns a new instance of Property
     *
     * @return Property
     */
    public function makeProperty()
    {
        return new Property();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     */
    public function canMakeInstance()
    {
        $property = $this->makeProperty();

        $this->assertNotNull($property);
    }

    /**
     * @test
     *
     * @covers ::getDefaultType
     */
    public function defaultTypeIsValue()
    {
        $property = $this->makeProperty();

        $this->assertSame(Type::VALUE, $property->getDefaultType());
    }
}