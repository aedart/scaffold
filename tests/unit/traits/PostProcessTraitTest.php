<?php

use Aedart\Scaffold\Traits\PostProcess;

/**
 * Class PostProcessTraitTest
 *
 * @group traits
 * @group postProcess
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class PostProcessTraitTest extends TraitsTestCase
{
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
     */
    public function canInvokeTraitMethods(){
        $this->assertGetterSetterTraitMethods(PostProcess::class, $this->makeCallableMethod(), $this->makeCallableMethod());
    }
}