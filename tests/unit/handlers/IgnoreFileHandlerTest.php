<?php
use Aedart\Scaffold\Handlers\IgnoreFileHandler;

/**
 * Class IgnoreFileHandlerTest
 *
 * @group handlers
 * @coversDefaultClass Aedart\Scaffold\Handlers\IgnoreFileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class IgnoreFileHandlerTest extends HandlerTestCase{

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
     * @return IgnoreFileHandler
     */
    public function getIgnoreFileHandler() {
        $handler = new IgnoreFileHandler();

        $handler->setBasePath($this->getCopyFileLocation());
        $handler->setOutputPath($this->getOutputFileCopyLocation());

        return $handler;
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     */
    public function doesNotCopyFile() {
        $handler = $this->getIgnoreFileHandler();

        $file = $this->getCopyFileLocation() . '.gitkeep';

        $handler->handle($file);

        $this->assertFileNotExists($this->getOutputFileCopyLocation() . '.gitkeep', 'Should not be copied');
    }
}