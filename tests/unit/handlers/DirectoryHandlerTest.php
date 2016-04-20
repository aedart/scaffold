<?php
use Aedart\Scaffold\Handlers\DirectoryHandler;
use Illuminate\Contracts\Logging\Log;

/**
 * Class DirectoryHandlerTest
 *
 * @group handlers
 * @group directoryHandler
 *
 * @coversDefaultClass Aedart\Scaffold\Handlers\DirectoryHandler
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DirectoryHandlerTest extends BaseUnitTest
{
    protected function _after()
    {
        // Remove all created folders inside output path
        $fs = $this->getFilesystem();
        $folders = $fs->directories($this->outputPath());

        foreach($folders as $directory){
            $fs->deleteDirectory($directory);
        }

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
     * @return DirectoryHandler
     */
    public function makeDirectoryHandler(Log $log = null)
    {
        $handler = new DirectoryHandler();

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

        // Assert that all paths have been created
        foreach($paths as $path){
            $this->assertFileExists($this->outputPath() . $path, 'Path was not created by handler');
        }
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

    // TODO: Problem with test - how to make mkdir actually fail and return false?

//    /**
//     * @test
//     *
//     * @covers ::processDirectories
//     * @covers ::processElement
//     *
//     * @covers ::createDirectory
//     */
//    public function failsWhenUnableToCreateDirectory()
//    {
//        // Get some directory paths
//        $paths = $this->makeFolderPaths();
//
//        // Add invalid directory name
//        $path[] = '??????';
//
//        $directoriesCollection = $this->makeDirectoriesCollectionMock();
//        $directoriesCollection->shouldReceive('all')
//            //->once()
//            ->andReturn($paths);
//
//        // Get a log mock
//        $log = $this->makeLogMock();
//        $log->shouldReceive('info')
//            ->withAnyArgs();
//
//        // Get the directory handler
//        $handler = $this->makeDirectoryHandler($log);
//
//        // Process paths
//        $handler->processDirectories($directoriesCollection);
//    }
}