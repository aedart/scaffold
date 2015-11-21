<?php

use Aedart\Testing\Laravel\TestCases\unit\UnitTestCase;
use Codeception\Configuration;
use Illuminate\Filesystem\Filesystem;

/**
 * Handler Test Case
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class HandlerTestCase extends UnitTestCase{

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Create a directory if it doesn't already exist
     *
     * @param string $directory
     */
    public function createLocation($directory) {
        $fs = new Filesystem();
        if(!$fs->isDirectory($directory)){
            $fs->makeDirectory($directory);
        }
    }

    /**
     * Remove a directory if it exists
     *
     * @param string $directory
     */
    public function removeLocation($directory) {
        $fs = new Filesystem();
        if($fs->isDirectory($directory)){
            $fs->deleteDirectory($directory);
        }
    }

    /**
     * Returns location to where the test-directories are found
     *
     * @return string
     */
    public function getDirectoriesLocation() {
        return Configuration::dataDir() . 'handlers/directories/';
    }

    /**
     * Returns location to output folder
     *
     * @return string
     */
    public function getOutputDirectoriesLocation() {
        return Configuration::outputDir() . 'handlers/directories/';
    }
}