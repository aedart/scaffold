<?php

use Aedart\Scaffold\Traits\IndexDirectoryPath;
use Aedart\Testing\Laravel\TestCases\unit\GetterSetterTraitTestCase;

/**
 * Class IndexDirectoryPathTest
 *
 * @group traits
 * @group indexes
 *
 * @coversDefaultClass Aedart\Scaffold\Traits\IndexDirectoryPath
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class IndexDirectoryPathTest extends GetterSetterTraitTestCase
{

    /**
     * Returns the class path to the trait in question
     *
     * @return string
     */
    public function getTraitClassPath()
    {
        return IndexDirectoryPath::class;
    }

    /**
     * Returns the name of the property, which the given
     * trait has implemented its getter and setter methods
     *
     * @return string
     */
    public function propertyName()
    {
        return 'indexDirectoryPath';
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     *
     * @covers ::setIndexDirectoryPath
     * @covers ::getIndexDirectoryPath
     * @covers ::hasIndexDirectoryPath
     * @covers ::hasDefaultIndexDirectoryPath
     * @covers ::getDefaultIndexDirectoryPath
     */
    public function runIndexDirectoryPathTraitMethods()
    {
        $this->assertGetterSetterTraitMethods($this->faker->word, $this->faker->word);
    }
}