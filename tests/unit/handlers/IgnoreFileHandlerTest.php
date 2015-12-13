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
        $this->createLocation($this->getOutputCopyFileLocation());
    }

    protected function _after(){
        // Remove any eventual directories inside the output location
        $this->removeLocation($this->getOutputCopyFileLocation());

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
        $handler->setOutputPath($this->getOutputCopyFileLocation());

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

        $this->assertFileNotExists($this->getOutputCopyFileLocation() . '.gitkeep', 'Should not be copied');
    }
}