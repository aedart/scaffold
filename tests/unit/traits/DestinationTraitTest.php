<?php
use Aedart\Scaffold\Traits\Destination;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;

/**
 * Class DestinationTraitTest
 *
 * @group traits
 * @group destination
 *
 * @coversDefaultClass Aedart\Scaffold\Traits\Destination
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DestinationTraitTest extends GetterSetterTraitTestCase
{

    use PropertyUtil;

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath()
    {
        return Destination::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName()
    {
        return 'destination';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setDestination
     * @covers ::getDestination
     * @covers ::hasDestination
     * @covers ::hasDefaultDestination
     * @covers ::getDefaultDestination
     */
    public function runDestinationTraitMethods()
    {
        $this->assertGetterSetterTraitMethods($this->makePropertyMock(), $this->makePropertyMock());
    }
}