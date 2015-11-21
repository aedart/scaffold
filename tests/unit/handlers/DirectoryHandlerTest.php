<?php

use Aedart\Scaffold\Handlers\DirectoryHandler;
use Codeception\Util\Debug;
use Illuminate\Filesystem\Filesystem;

/**
 * Class DirectoryHandlerTest
 *
 * @group handlers
 * @coversDefaultClass Aedart\Scaffold\Handlers\DirectoryHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DirectoryHandlerTest extends HandlerTestCase{

    protected function _before() {
        parent::_before();

        // Add output location directory, in case it doesn't exist
        $this->createLocation($this->getOutputDirectoriesLocation());
    }

    protected function _after(){
        // Remove any eventual directories inside the output location
        $this->removeLocation($this->getOutputDirectoriesLocation());

        parent::_after();
    }

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Get the handler in question
     *
     * @return DirectoryHandler
     */
    public function getDirectoryHandler() {
        $handler = new DirectoryHandler();

        $handler->setFile(new Filesystem());
        $handler->setBasePath($this->getDirectoriesLocation());
        $handler->setOutputPath($this->getOutputDirectoriesLocation());

        return $handler;
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::directoriesIn
     */
    public function canObtainDirectories() {
        $handler = $this->getDirectoryHandler();

        $directories = $handler->directoriesIn($this->getDirectoriesLocation());

        Debug::debug('Directories found: ' . var_export($directories, true));

        $this->assertCount(8, $directories);
    }

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     * @covers ::directoriesIn
     * @covers ::makeDirectories
     */
    public function canCreateDirectories() {
        $handler = $this->getDirectoryHandler();

        // NOTE: "root" path is set via the 'setBasePath' method
        $handler->handle('');

        // Obtain the target directories and the output directories
        $targetDirectories = $handler->directoriesIn($this->getDirectoriesLocation());
        $outputDirectories = $handler->directoriesIn($this->getOutputDirectoriesLocation());

        // Assert amount of directories
        $this->assertCount(count($targetDirectories), $outputDirectories, 'Incorrect amount of directories created');

        // Assert that the same directories exist in the output, as in the target location
        foreach($targetDirectories as $targetDirectory){
            $this->assertContains($targetDirectory, $outputDirectories, 'Target directory has NOT been created in output directory');
        }
    }

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     * @covers ::directoriesIn
     * @covers ::makeDirectories
     */
    public function doesNotThrowExceptionIfDirectoriesAlreadyExist() {
        $handler = $this->getDirectoryHandler();

        // Nothing should exist in output location
        $handler->handle('');

        // Now, however, all directories should had been created,
        // in which case re-invoking the method should not do
        // anything!
        $handler->handle('');

        $this->assertTrue(true);
    }
}