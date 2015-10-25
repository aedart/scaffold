<?php
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;
use Aedart\Scaffold\Traits\DirectoryHandlerTrait;

/**
 * Class DirectoryHandlerTraitTest
 *
 * @group traits
 * @coversDefaultClass Aedart\Scaffold\Traits\DirectoryHandlerTrait
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DirectoryHandlerTraitTest extends GetterSetterTraitTestCase{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath() {
        return DirectoryHandlerTrait::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName() {
        return 'directoryHandler';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setDirectoryHandler
     * @covers ::getDirectoryHandler
     * @covers ::hasDirectoryHandler
     * @covers ::hasDefaultDirectoryHandler
     * @covers ::getDefaultDirectoryHandler
     */
    public function directoryHandlerTraitMethods(){
        $this->assertGetterSetterTraitMethods($this->faker->word . '/' . $this->faker->word, $this->faker->word . '/' . $this->faker->word);
    }
}