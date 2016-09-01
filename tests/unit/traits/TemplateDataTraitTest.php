<?php

use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Templates\TemplateData;
use Mockery as m;

/**
 * Class TemplateDataTraitTest
 *
 * @group traits
 * @group templates
 * @group templateData
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplateDataTraitTest extends TraitsTestCase
{

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
     */
    public function canInvokeTraitMethods(){
        $this->assertGetterSetterTraitMethods(TemplateData::class, $this->makeCollection(), $this->makeCollection());
    }
}