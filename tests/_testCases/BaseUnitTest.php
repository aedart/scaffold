<?php

use Aedart\Scaffold\Contracts\Collections\Directories;
use Codeception\Configuration;
use Codeception\TestCase\Test;
use Codeception\Util\Debug;
use Faker\Factory;
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
abstract class BaseUnitTest extends Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Instance of the faker generator
     *
     * @var \Faker\Generator
     */
    protected $faker;

    protected function _before()
    {
        // Faker instance
        $this->faker = Factory::create();

        // Create output path if it does not exist
        if(!file_exists($this->outputPath())){
            mkdir($this->outputPath(), 0755, true);

            Debug::debug(sprintf('<info>Created output path </info><debug>%s</debug>', $this->outputPath()));
        }
    }

    protected function _after()
    {
        m::close();
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

    /**
     * Returns the output directory path
     *
     * @return string
     * @throws \Codeception\Exception\ConfigurationException
     */
    public function outputPath()
    {
        return Configuration::outputDir();
    }
}