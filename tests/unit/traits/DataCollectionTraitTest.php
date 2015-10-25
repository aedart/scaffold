<?php
use Aedart\Scaffold\Collections\AskablePropertiesCollection;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use Aedart\Scaffold\Traits\DataCollectionTrait;

/**
 * Class DataCollectionTraitTest
 *
 * @group traits
 * @coversDefaultClass Aedart\Scaffold\Traits\DataCollectionTrait
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DataCollectionTraitTest extends GetterSetterTraitTestCase{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath() {
        return DataCollectionTrait::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName() {
        return 'data';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setData
     * @covers ::getData
     * @covers ::hasData
     * @covers ::hasDefaultData
     * @covers ::getDefaultData
     */
    public function dataCollectionTraitMethods(){
        $this->assertGetterSetterTraitMethods(new AskablePropertiesCollection(), new AskablePropertiesCollection());
    }
}