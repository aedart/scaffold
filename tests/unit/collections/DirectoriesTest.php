<?php

use Aedart\Scaffold\Collections\Directories;
use Codeception\Util\Debug;

/**
 * Class DirectoriesTest
 *
 * @group collections
 * @group directories
 *
 * @coversDefaultClass Aedart\Scaffold\Collections\Directories
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DirectoriesTest extends BaseUnitTest
{

    /**
     * Returns a new instance of the Directories Collection
     *
     * @param array $paths
     *
     * @return Directories
     */
    public function makeDirectoriesCollection(array $paths = [])
    {
        $collection = new Directories($paths);
        return $collection;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     * @covers ::add
     */
    public function canAddPaths()
    {
        $paths = $this->makeFolderPaths(rand(3, 10));
        $collection = $this->makeDirectoriesCollection($paths);

        Debug::debug($collection);

        $this->assertCount(count($paths), $collection, 'Did not add correct amount of paths');
    }

    /**
     * @test
     *
     * @covers ::all
     */
    public function canObtainAllPaths()
    {
        $paths = $this->makeFolderPaths(rand(3, 10));
        $collection = $this->makeDirectoriesCollection($paths);

        Debug::debug($collection);

        $this->assertSame($paths, $collection->all());
    }
}