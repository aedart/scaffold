<?php
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use Aedart\Scaffold\Traits\HandlerTrait;

/**
 * Class HandlerTraitTest
 *
 * NB: In this test we do not really care if a class path is
 * correct or not. Validation falls outside the scope of the
 * given trait
 *
 * @group traits
 * @coversDefaultClass Aedart\Scaffold\Traits\HandlerTrait
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class HandlerTraitTest extends GetterSetterTraitTestCase{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath() {
        return HandlerTrait::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName() {
        return 'handler';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setHandler
     * @covers ::getHandler
     * @covers ::hasHandler
     * @covers ::hasDefaultHandler
     * @covers ::getDefaultHandler
     */
    public function handlerTraitMethods(){
        $this->assertGetterSetterTraitMethods($this->faker->word . '/' . $this->faker->word, $this->faker->word . '/' . $this->faker->word);
    }
}