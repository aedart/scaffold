<?php

use Aedart\Scaffold\Traits\PostProcess;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;

/**
 * Class PostProcessTraitTest
 *
 * @group traits
 * @group postProcess
 *
 * @coversDefaultClass Aedart\Scaffold\Traits\PostProcess
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class PostProcessTraitTest extends GetterSetterTraitTestCase
{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath()
    {
        return PostProcess::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName()
    {
        return 'postProcess';
    }

    /**
     * Returns a new closure
     *
     * @return Closure
     */
    public function makeCallableMethod()
    {
        return function(){
            // N/A;
            $x = mt_rand(1, 10);
            return $x;
        };
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setPostProcess
     * @covers ::getPostProcess
     * @covers ::hasPostProcess
     * @covers ::hasDefaultPostProcess
     * @covers ::getDefaultPostProcess
     */
    public function runPostProcessTraitMethods(){
        $this->assertGetterSetterTraitMethods($this->makeCallableMethod(), $this->makeCallableMethod());
    }
}