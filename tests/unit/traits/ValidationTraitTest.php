<?php

use Aedart\Scaffold\Traits\Validation;

/**
 * Class ValidationTraitTest
 *
 * @group traits
 * @group validation
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ValidationTraitTest extends TraitsTestCase
{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath()
    {
        return Validation::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName()
    {
        return 'validation';
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
     */
    public function canInvokeTraitMethods(){
        $this->assertGetterSetterTraitMethods(Validation::class, $this->makeCallableMethod(), $this->makeCallableMethod());
    }
}