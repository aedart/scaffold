<?php
use Aedart\Scaffold\Collections\TemplatesCollection;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use Aedart\Scaffold\Traits\TemplatesCollectionTrait;

/**
 * Class TemplatesCollectionTraitTest
 *
 * @group traits
 * @coversDefaultClass Aedart\Scaffold\Traits\TemplatesCollectionTrait
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplatesCollectionTraitTest extends GetterSetterTraitTestCase{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath() {
        return TemplatesCollectionTrait::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName() {
        return 'templates';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setTemplates
     * @covers ::getTemplates
     * @covers ::hasTemplates
     * @covers ::hasDefaultTemplates
     * @covers ::getDefaultTemplates
     */
    public function templatesCollectionTraitMethods(){
        $this->assertGetterSetterTraitMethods(new TemplatesCollection(), new TemplatesCollection());
    }
}