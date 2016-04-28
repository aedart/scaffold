<?php

use Aedart\Scaffold\Handlers\FilesHandler;
use Illuminate\Contracts\Logging\Log;

/**
 * Class FilesHandlerTest
 *
 * @group handlers
 * @group filesHandler
 *
 * @coversDefaultClass Aedart\Scaffold\Handlers\FilesHandler
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class FilesHandlerTest extends BaseUnitTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function dataPath()
    {
        return parent::dataPath() . 'handlers/files/';
    }

    public function outputPath()
    {
        return parent::outputPath() . 'handlers/files/';
    }

    /**
     * Returns a list of source files and their
     * destinations
     *
     * @return array
     */
    public function getFilesList()
    {
        return [
            '.gitkeep'      =>  '.gitkeep',
            'LICENSE'       =>  'LICENSE.txt',
            'README.md'     =>  'README',
        ];
    }

    /**
     * Returns a new Files handler instance
     *
     * @param Log|null $log [optional]
     *
     * @return FilesHandler
     */
    public function makeFilesHandler(Log $log = null)
    {
        $handler = new FilesHandler();

        $handler->setBasePath($this->dataPath());
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
     * @covers ::processFiles
     * @covers ::processElement
     *
     * @covers ::copyFile
     */
    public function canCopyFiles()
    {
        $files = $this->getFilesList();
        $filesCollection = $this->makeFilesCollectionMock();
        $filesCollection->shouldReceive('all')
            ->andReturn($files);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeFilesHandler($log);

        // Process paths
        $handler->processFiles($filesCollection);

        $paths = array_values($files);
        $this->assertPathsOrFilesExist($paths, 'File was not created by handler');
    }

    /**
     * @test
     *
     * @covers ::processFiles
     * @covers ::processElement
     *
     * @covers ::copyFile
     */
    public function skipsExistingFiles()
    {
        $files = $this->getFilesList();
        $filesCollection = $this->makeFilesCollectionMock();
        $filesCollection->shouldReceive('all')
            ->andReturn($files);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeFilesHandler($log);

        // Process paths
        $handler->processFiles($filesCollection);

        // At this point, the files have been copied
        // Therefore, if executed again, none of the directories
        // should be created. We can test via the amount of
        // calls to the log-instance's "notice
        $log = $this->makeLogMock();
        $log->shouldReceive('notice')
            ->times(count($files))
            ->withAnyArgs();

        // Re-Process paths
        $handler->setLog($log);
        $handler->processFiles($filesCollection);
    }

    /**
     * @test
     *
     * @covers ::copyFile
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotCopyFileException
     */
    public function failsWhenUnableToCopyFile()
    {
        $files = $this->getFilesList();
        $filesCollection = $this->makeFilesCollectionMock();
        $filesCollection->shouldReceive('all')
            ->andReturn($files);

        // Filesystem mock that fails to copy
        $filesystem = $this->makeFilesystemMock();

        $filesystem->shouldReceive('exists')
            ->andReturn(false);

        $filesystem->shouldReceive('copy')
            ->withAnyArgs()
            ->andReturn(false);

        $log = $this->makeLogMock();
        $log->shouldNotReceive('info');

        $handler = $this->makeFilesHandler($log);
        $handler->setFile($filesystem);

        $handler->processFiles($filesCollection);
    }
}