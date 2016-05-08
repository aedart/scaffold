<?php

use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Scaffold\Contracts\Collections\Files;
use Aedart\Testing\Laravel\TestCases\unit\UnitTestCase;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;
use Symfony\Component\Console\Style\StyleInterface;

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
    use PropertyUtil;
    use TemplateUtil;

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
     * Returns a files collection mock
     *
     * @return m\MockInterface|Files
     */
    public function makeFilesCollectionMock()
    {
        return m::mock(Files::class);
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
     * Returns random source files and their belonging
     * destination
     *
     * WARNING: All paths are fake, don't use this for
     * testing actual handler implementation!
     *
     * @param int $amount
     *
     * @return string[]
     */
    public function makeFilesPaths($amount = 3)
    {
        $output = [];

        while($amount--){
            $nested = mt_rand(1, 4);
            $filename = $this->faker->word . '.' . $this->faker->fileExtension;
            $sourcePath = [];

            while($nested--){
                $sourcePath[] = $this->faker->word;
            }

            $nested = mt_rand(1, 4);
            $destinationPath = [];
            while($nested--){
                $destinationPath[] = $this->faker->word;
            }

            $sourceFile = implode(DIRECTORY_SEPARATOR, $sourcePath) . DIRECTORY_SEPARATOR . $filename;
            $destination = implode(DIRECTORY_SEPARATOR, $destinationPath) . DIRECTORY_SEPARATOR . $filename;

            $output[$sourceFile] = $destination;
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

    /**
     * Returns a style interface (output) mock
     *
     * @return m\MockInterface|StyleInterface
     */
    public function makeStyleInterfaceMock()
    {
        return m::mock(StyleInterface::class);
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