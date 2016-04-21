<?php

use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Testing\Laravel\TestCases\unit\UnitTestCase;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

/**
 * Base UnitTest
 *
 * The core test-class for all unit tests
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class BaseUnitTest extends UnitTestCase
{
    use DataPath;
    use OutputPath;
    use IoCDestroyer;

    protected function _before()
    {
        parent::_before();

        $this->createOutputPath();
    }

    protected function _after()
    {
        $this->destroyIoC();

        parent::_after();
    }

    /********************************************************
     * Helpers
     *******************************************************/

    /**
     * Returns instance of a log mock
     *
     * @return m\MockInterface|Log
     */
    public function makeLogMock()
    {
        return m::mock(Log::class);
    }

    /**
     * Returns a directories collection mock
     *
     * @return m\MockInterface|Directories
     */
    public function makeDirectoriesCollectionMock()
    {
        return m::mock(Directories::class);
    }

    /**
     * Returns filesytem mock
     *
     * @return m\MockInterface|Filesystem
     */
    public function makeFilesystemMock()
    {
        return m::mock(Filesystem::class);
    }

    /**
     * Returns a Configuration Repository mock
     *
     * @return m\MockInterface|Repository
     */
    public function makeConfigRepositoryMock()
    {
        return m::mock(Repository::class);
    }

    /**
     * Returns random folder paths
     *
     * @param int $amount [optional]
     *
     * @return string[]
     */
    public function makeFolderPaths($amount = 3)
    {
        $output = [];

        while($amount--){
            $nested = mt_rand(1, 4);
            $path = [];

            while($nested--){
                $path[] = $this->faker->word;
            }

            $output[] = implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR;
        }

        return $output;
    }

    /**
     * Get a filesystem instance
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return new Filesystem();
    }

    /********************************************************
     * Custom assertions
     *******************************************************/

    /**
     * Assert that the given list of paths or files exist
     * inside the output path
     *
     * @see outputPath()
     *
     * @param array $paths
     * @param string $message [optional]
     */
    public function assertPathsOrFilesExist(array $paths, $message = 'Path does not exist')
    {
        foreach($paths as $path){
            $this->assertFileExists($this->outputPath() . $path, 'Path does not exist');
        }
    }
}