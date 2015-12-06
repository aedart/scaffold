<?php
use Aedart\Scaffold\Contracts\Engines\TemplateEngine;
use Aedart\Scaffold\Traits\TemplateEngineTrait;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use \Mockery as m;

/**
 * Class TemplateEngineTraitTest
 *
 * @group traits
 * @coversDefaultClass Aedart\Scaffold\Traits\TemplateEngineTrait
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplateEngineTraitTest extends GetterSetterTraitTestCase{

    /***********************************************************
     * helpers
     **********************************************************/

    /**
     * A a template engine mock
     *
     * @return m\MockInterface|TemplateEngine
     */
    public function getTemplateEngineMock() {
        return m::mock(TemplateEngine::class);
    }

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath() {
        return TemplateEngineTrait::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName() {
        return 'templateEngine';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setTemplateEngine
     * @covers ::getTemplateEngine
     * @covers ::hasTemplateEngine
     * @covers ::hasDefaultTemplateEngine
     * @covers ::getDefaultTemplateEngine
     */
    public function templateEngineTraitMethods(){
        $engine = $this->getTemplateEngineMock();
        $this->assertGetterSetterTraitMethods($engine, null);
    }

}