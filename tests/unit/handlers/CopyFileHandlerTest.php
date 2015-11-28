<?php
use Aedart\Scaffold\Handlers\CopyFileHandler;
use Illuminate\Filesystem\Filesystem;

/**
 * Class CopyFileHandlerTest
 *
 * @group handlers
 * @coversDefaultClass Aedart\Scaffold\Handlers\CopyFileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class CopyFileHandlerTest extends HandlerTestCase{

    protected function _before() {
        parent::_before();

        // Add output location directory, in case it doesn't exist
        $this->createLocation($this->getOutputFileCopyLocation());
    }

    protected function _after(){
        // Remove any eventual directories inside the output location
        $this->removeLocation($this->getOutputFileCopyLocation());

        parent::_after();
    }

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Get the handler in question
     *
     * @return CopyFileHandler
     */
    public function getCopyFileHandler() {
        $handler = new CopyFileHandler();

        $handler->setFile(new Filesystem());
        $handler->setBasePath($this->getCopyFileLocation());
        $handler->setOutputPath($this->getOutputFileCopyLocation());

        return $handler;
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::resolveFilename
     */
    public function canResolveFilename() {
        $handler = $this->getCopyFileHandler();

        $file = $this->getCopyFileLocation() . 'hallo.txt';

        $handler->resolveFilename($file);

        $this->assertSame('hallo.txt', $handler->getFilename());
    }

    /**
     * @test
     * @covers ::resolveFilename
     */
    public function doesNotResolveFilenameWhenAlreadySpecified() {
        $handler = $this->getCopyFileHandler();

        $file = $this->getCopyFileLocation() . 'hallo.txt';

        $handler->setFilename('jimmy.txt');

        $handler->resolveFilename($file);

        $this->assertSame('jimmy.txt', $handler->getFilename());
    }

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     */
    public function canCopyFile() {
        $handler = $this->getCopyFileHandler();

        $file = $this->getCopyFileLocation() . 'hallo.txt';

        $handler->handle($file);

        $this->assertFileExists($this->getOutputFileCopyLocation() . 'hallo.txt', 'File was not copied!');
    }

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     *
     * @expectedException \Aedart\Scaffold\Exceptions\UnableToHandleElementException
     */
    public function failsWhenFileAlreadyExists() {
        $handler = $this->getCopyFileHandler();

        $file = $this->getCopyFileLocation() . 'hallo.txt';

        $handler->handle($file);

        // triggering the handler twice on the same file should force a failure
        $handler->handle($file);
    }
}