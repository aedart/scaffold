<?php

use Aedart\Scaffold\ScaffoldApplication;

/**
 * Class ScaffoldApplicationTest
 *
 * @group application
 * @group scaffoldApplication
 *
 * @coversDefaultClass Aedart\Scaffold\ScaffoldApplication
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ScaffoldApplicationTest extends ConsoleTest
{
    public function makeApplication()
    {
        return new ScaffoldApplication();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     */
    public function canObtainInstance()
    {
        $app = $this->makeApplication();

        $this->assertNotNull($app);
    }

    /**
     * @test
     *
     * @covers ::resolveApplicationVersion
     */
    public function canResolveApplicationVersion()
    {
        $app = $this->makeApplication();

        $version = $app->getVersion();

        $this->assertNotEmpty($version, 'No version resolved');
        $this->assertNotSame('UNKNOWN', $version, 'Version was not resolved from composer file');
    }

    /**
     * @test
     *
     * @covers ::setupApplication
     * @covers ::bootIoC
     * @covers ::registerCommands
     */
    public function hasPerformedSetupAfterInstantiation()
    {
        $app = $this->makeApplication();

        // The only real thing that we can test, is if
        // the app has registered some of its commands
        // that we know must be there...

        $this->assertTrue($app->has('build'));
    }
}