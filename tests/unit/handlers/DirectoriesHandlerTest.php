<?php
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Illuminate\Contracts\Logging\Log;

/**
 * Class DirectoryHandlerTest
 *
 * @group handlers
 * @group directoriesHandler
 *
 * @coversDefaultClass Aedart\Scaffold\Handlers\DirectoriesHandler
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DirectoriesHandlerTest extends BaseUnitTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }


    public function outputPath()
    {
        return parent::outputPath() . 'handlers/directories/';
    }

    /**
     * Returns a new Directory handler instance
     *
     * @param Log|null $log [optional]
     *
     * @return DirectoriesHandler
     */
    public function makeDirectoryHandler(Log $log = null)
    {
        $handler = new DirectoriesHandler();

        $handler->setOutputPath($this->outputPath());
        $handler->setFile($this->getFilesystem());

        if(!is_null($log)){
            $handler->setLog($log);
        }

        return $handler;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::processDirectories
     * @covers ::processElement
     *
     * @covers ::createDirectory
     */
    public function canCreateDirectories()
    {
        // Get some directory paths
        $paths = $this->makeFolderPaths();
        $directoriesCollection = $this->makeDirectoriesCollectionMock();
        $directoriesCollection->shouldReceive('all')
            ->once()
            ->andReturn($paths);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeDirectoryHandler($log);

        // Process paths
        $handler->processDirectories($directoriesCollection);

        $this->assertPathsOrFilesExist($paths, 'Path was not created by handler');
    }

    /**
     * @test
     *
     * @covers ::processDirectories
     * @covers ::processElement
     *
     * @covers ::createDirectory
     */
    public function skipsExistingDirectories()
    {
        // Get some directory paths
        $paths = $this->makeFolderPaths();
        $directoriesCollection = $this->makeDirectoriesCollectionMock();
        $directoriesCollection->shouldReceive('all')
            //->once()
            ->andReturn($paths);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeDirectoryHandler($log);

        // Process paths
        $handler->processDirectories($directoriesCollection);

        // At this point, the directories are created.
        // Therefore, if executed again, none of the directories
        // should be created. We can test via the amount of
        // calls to the log-instance's "notice
        $log = $this->makeLogMock();
        $log->shouldReceive('notice')
            ->times(count($paths))
            ->withAnyArgs();

        // Re-Process paths
        $handler->setLog($log);
        $handler->processDirectories($directoriesCollection);
    }

    /**
     * @test
     *
     * @covers ::createDirectory
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotCreateDirectoryException
     */
    public function failsWhenUnableMakeDirectory()
    {
        // Get some directory paths
        $paths = $this->makeFolderPaths();
        $directoriesCollection = $this->makeDirectoriesCollectionMock();
        $directoriesCollection->shouldReceive('all')
            //->once()
            ->andReturn($paths);

        // Filesystem mock that fails to make directories
        $filesystem = $this->makeFilesystemMock();

        $filesystem->shouldReceive('exists')
            ->andReturn(false);

        $filesystem->shouldReceive('makeDirectory')
            ->withAnyArgs()
            ->andReturn(false);

        $log = $this->makeLogMock();
        $log->shouldNotReceive('info');

        $handler = $this->makeDirectoryHandler($log);
        $handler->setFile($filesystem);

        $handler->processElement($directoriesCollection);
    }
}