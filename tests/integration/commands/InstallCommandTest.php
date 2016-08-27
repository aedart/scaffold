<?php

/**
 * InstallCommandTest
 *
 * @group commands
 * @group installCommand
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class InstallCommandTest extends BaseIntegrationTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function outputPath()
    {
        return parent::outputPath() . 'install/';
    }

    public function dataPath()
    {
        return parent::dataPath() . 'install/';
    }

    public function getVendorsList()
    {
        return [
            $this->dataPath() . 'vendorA/'
        ];
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     */
    public function canObtainCommandHelpText()
    {
        $command = $this->getCommandFromApp('install');

        $txt = $command->getHelp();

        $this->assertNotEmpty($txt);
    }

    /**
     * @test
     */
    public function canInstallScaffold()
    {
        $input = [
            '0', // Select vendor
            '0', // Select package
            '0', // Select scaffold
            'TvController', // Class Name
            'src/Controllers/TvController.php' // Filename
        ];

        $args = [
            '--output'              => $this->outputPath(),
            '--index-directories'   => $this->getVendorsList(),
            '--index-output'        => $this->outputPath() . '.scaffold/'
        ];

        $this->executeInteractiveCommand('install', $args, $input);

        // If there was no exception, we assume everything is ok in this test
        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @depends canInstallScaffold
     */
    public function hasGeneratedFilesCorrectly()
    {
        // Basically, this is the same test as before...
        // Yet here we do test the output / result of the install!

        $input = [
            '0', // Select vendor
            '0', // Select package
            '0', // Select scaffold
            'TvController', // Class Name
            'src/Controllers/TvController.php' // Filename
        ];

        $args = [
            '--output'              => $this->outputPath(),
            '--index-directories'   => $this->getVendorsList(),
            '--index-output'        => $this->outputPath() . '.scaffold/'
        ];

        $this->executeInteractiveCommand('install', $args, $input);

        // ----------------------------------------------------- //

        // Assert that files exist
        $this->assertPathsOrFilesExist([
            '.scaffold/index.json',
            'src/Controllers/TvController.php',
            'README.md',
        ]);

        // Load in the created php class and check that it actually
        // works! If so, then we know that it was written and built
        // correctly.
        require $this->outputPath() . 'src/Controllers/TvController.php';

        $name = 'TvController';
        $controller = new $name();

        $channel = $this->faker->word;

        $expected = 'You are watching ' . $channel;
        $result = $controller->watch($channel);

        $this->assertSame($expected, $result);
    }
}