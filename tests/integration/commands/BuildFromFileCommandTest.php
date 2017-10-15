<?php

use Symfony\Component\Console\Output\OutputInterface;

/**
 * BuildFromFileCommandTest
 *
 * @group commands
 * @group build-from-file-cmd
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BuildFromFileCommandTest extends BaseIntegrationTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function outputPath()
    {
        return parent::outputPath() . 'build/';
    }

    public function dataPath()
    {
        return parent::dataPath() . 'build/';
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     */
    public function canObtainCommandHelpText()
    {
        $command = $this->getCommandFromApp('build:file');

        $txt = $command->getHelp();

        $this->assertNotEmpty($txt);
    }

    /**
     * @test
     */
    public function canBuildSingleScaffold()
    {
        $command = $this->getCommandFromApp('build:file');

        $commandTester = $this->makeCommandTester($command);

        $expected = strtolower('AEDART/input-from-file');

        $commandTester->execute([
            'command'       => $command->getName(),
            'file'          => $this->dataPath() . 'singleScaffold.php',
        ], [
            'verbosity'   => OutputInterface::VERBOSITY_VERY_VERBOSE
        ]);

        $this->assertContains($expected, $commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function canBuildMultipleScaffolds()
    {
        $command = $this->getCommandFromApp('build:file');

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'   => $command->getName(),
            'file'    => $this->dataPath() . 'multipleScaffolds.php',
        ], [
            'verbosity'   => OutputInterface::VERBOSITY_VERY_VERBOSE
        ]);

        // Fetch display
        $display = $commandTester->getDisplay();

        // Assert multiple...
        $this->assertContains(strtolower('AEDART/a'), $display);
        $this->assertContains(strtolower('Acme/b'), $display);
        $this->assertContains(strtolower('Punk/c'), $display);
    }
}