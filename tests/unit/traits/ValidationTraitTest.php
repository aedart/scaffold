<?php

use Aedart\Scaffold\Traits\Validation;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;

/**
 * Class ValidationTraitTest
 *
 * @group traits
 * @group validation
 *
 * @coversDefaultClass Aedart\Scaffold\Traits\Validation
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ValidationTraitTest extends GetterSetterTraitTestCase
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
     *
     * @covers ::setValidation
     * @covers ::getValidation
     * @covers ::hasValidation
     * @covers ::hasDefaultValidation
     * @covers ::getDefaultValidation
     */
    public function runValidationTraitMethods(){
        $this->assertGetterSetterTraitMethods($this->makeCallableMethod(), $this->makeCallableMethod());
    }
}