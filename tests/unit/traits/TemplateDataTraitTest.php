<?php

use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Templates\TemplateData;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use Mockery as m;

/**
 * Class TemplateDataTraitTest
 *
 * @group traits
 * @group templates
 * @group templateData
 *
 * @coversDefaultClass Aedart\Scaffold\Templates\TemplateData
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplateDataTraitTest extends GetterSetterTraitTestCase
{

    public function _after()
    {
        m::close();
    }
    
    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath()
    {
        return TemplateData::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName()
    {
        return 'templateData';
    }

    /**
     * Returns a Template Properties collection mock
     *
     * @return m\MockInterface|TemplateProperties
     */
    public function makeCollection()
    {
        return m::mock(TemplateProperties::class);
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setTemplateData
     * @covers ::getTemplateData
     * @covers ::hasTemplateData
     * @covers ::hasDefaultTemplateData
     * @covers ::getDefaultTemplateData
     */
    public function runTemplateDataTraitMethods(){
        $this->assertGetterSetterTraitMethods($this->makeCollection(), $this->makeCollection());
    }
}