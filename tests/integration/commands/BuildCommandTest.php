<?php

/**
 * Class BuildCommandTest
 *
 * @group commands
 * @group buildCommand
 *
 * @coversDefaultClass Aedart\Scaffold\Console\BuildCommand
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BuildCommandTest extends BaseIntegrationTest
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
     *
     * @covers ::formatHelp
     * @covers ::formatTasksDescriptions
     */
    public function canObtainCommandHelpText()
    {
        $command = $this->getCommandFromApp('build');

        $txt = $command->getHelp();

        $this->assertNotEmpty($txt);
    }

    /**
     * @test
     *
     * @covers ::runCommand
     *
     * @covers ::loadAndResolveConfiguration
     * @covers ::configure
     *
     * @covers \Aedart\Scaffold\Facades\TaskRunner::getFacadeAccessor
     *
     * @covers \Aedart\Scaffold\Tasks\CreateDirectories::performTask
     *
     * @covers \Aedart\Scaffold\Tasks\CreateDirectories::parseDirectories
     * @covers \Aedart\Scaffold\Tasks\CreateDirectories::transformIntoPaths
     * @covers \Aedart\Scaffold\Tasks\CreateDirectories::getDirectoriesHandler
     */
    public function canBuildFolders()
    {
        $command = $this->getCommandFromApp('build');

        $commandTester = $this->makeCommandTester($command);

        // Remove trailing slash form output path...
        // Only for testing a specific condition in buildCommand
        $outputPath = substr($this->outputPath(), 0, -1);

        $commandTester->execute([
            'command'   => $command->getName(),
            'config'    => $this->dataPath() . 'foldersOnly.scaffold.php',
            '--output'  => $outputPath
        ]);

        // @see _data/commands/build/foldersOnly.php
        $expectedPaths = [
            'app',
            'config',
            'src',
            'src/Contracts',
            'src/Contracts/Events',
            'src/Contracts/Listeners',
            'src/Contracts/Listeners/DbEntries',
            'src/Contracts/Listeners/Jobs',
            'src/Contracts/Listeners/Logs',
            'src/Contracts/Models',
            'src/Controllers',
            'src/Events',
            'src/Models',
            'tmp',
        ];

        $this->assertPathsOrFilesExist($expectedPaths);
    }

    /**
     * @test
     *
     * @covers ::runCommand
     *
     * @covers ::loadAndResolveConfiguration
     * @covers ::configure
     *
     * @covers \Aedart\Scaffold\Facades\TaskRunner::getFacadeAccessor
     *
     * @covers \Aedart\Scaffold\Tasks\CreateDirectories::performTask
     * @covers \Aedart\Scaffold\Tasks\CopyFiles::performTask
     *
     * @covers \Aedart\Scaffold\Tasks\CopyFiles::parseFiles
     * @covers \Aedart\Scaffold\Tasks\CopyFiles::getFilesHandler
     */
    public function canCopyFiles()
    {
        $command = $this->getCommandFromApp('build');

        $commandTester = $this->makeCommandTester($command);

        // Remove trailing slash form output path...
        // Only for testing a specific condition in buildCommand
        $outputPath = substr($this->outputPath(), 0, -1);

        $commandTester->execute([
            'command'   => $command->getName(),
            'config'    => $this->dataPath() . 'filesOnly.scaffold.php',
            '--output'  => $outputPath
        ]);

        // @see _data/commands/build/filesOnly.php
        $expectedPaths = [
           '.gitkeep',
           'log.log',
           'LICENSE',
           'README.md',
        ];

        $this->assertPathsOrFilesExist($expectedPaths);
    }
}