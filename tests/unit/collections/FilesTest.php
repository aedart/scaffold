<?php

use Aedart\Scaffold\Collections\Files;
use Codeception\Util\Debug;

/**
 * Class FilesTest
 *
 * @group collections
 * @group files
 *
 * @coversDefaultClass Aedart\Scaffold\Collections\Files
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class FilesTest extends BaseUnitTest
{

    /**
     * Returns a new Files Collection instance
     *
     * @param string[] $files
     *
     * @return Files
     */
    public function makeFilesCollection(array $files = [])
    {
        return new Files($files);
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     * @covers ::put
     */
    public function canAddSourceFiles()
    {
        $paths = $this->makeFilesPaths(rand(3, 10));
        $collection = $this->makeFilesCollection($paths);

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
        $paths = $this->makeFilesPaths(rand(3, 10));
        $collection = $this->makeFilesCollection($paths);

        Debug::debug($collection);

        $this->assertSame($paths, $collection->all());
    }
}