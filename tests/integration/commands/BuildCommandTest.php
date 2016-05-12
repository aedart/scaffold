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

    /**
     * Returns a input stream
     *
     * Utility method for helping to test commands that
     * require interaction.
     *
     * @see http://symfony.com/doc/current/components/console/helpers/questionhelper.html#testing-a-command-that-expects-input
     *
     * @param $input
     *
     * @return resource
     */
    public function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::formatHelp
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

    /**
     * NOTE: This test does NOT handle user interaction. It therefore
     * only tests if the given output display contains a processed value.
     * See <i>_data/commands/build/templateDataOnly.scaffold.php</i> for further
     * details
     *
     * @test
     *
     * @covers ::runCommand
     *
     * @covers ::loadAndResolveConfiguration
     * @covers ::configure
     *
     * @covers \Aedart\Scaffold\Facades\TaskRunner::getFacadeAccessor
     *
     * @covers \Aedart\Scaffold\Tasks\AskForTemplateData::performTask
     *
     * @covers \Aedart\Scaffold\Collections\Utility\PropertiesCollectionParser::parsePropertiesCollection
     * @covers \Aedart\Scaffold\Handlers\Utility\PropertyHandlerResolver::makePropertyHandler
     */
    public function canAskForTemplateData()
    {
        //$givenInput = ' ACME/TEST-PACKAGE ';

        $command = $this->getCommandFromApp('build');

        // Will not work, because a task contains the Style Interface, which
        // is very hard to obtain at this level
        //$helper = $command->getHelper('question');
        //$helper->setInputStream($this->getInputStream($givenInput . '\\n'));

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'   => $command->getName(),
            'config'    => $this->dataPath() . 'templateDataOnly.scaffold.php',
        ]);

        //$expectedPackageName = strtolower(trim($givenInput));

        $expectedPackageName = 'aedart/scaffold-example';

        $this->assertContains($expectedPackageName, $commandTester->getDisplay());
    }

    /**
     * NOTE: This test does NOT handle user interaction. It therefore
     * only tests if the given output display contains a processed value.
     * See <i>_data/commands/build/templateOnly.scaffold.php</i> for further
     * details
     *
     * @test
     *
     * @covers ::runCommand
     *
     * @covers ::loadAndResolveConfiguration
     * @covers ::configure
     *
     * @covers \Aedart\Scaffold\Facades\TaskRunner::getFacadeAccessor
     *
     * @covers \Aedart\Scaffold\Tasks\AskForTemplateDestination::performTask
     *
     * @covers \Aedart\Scaffold\Collections\Utility\TemplateCollectionParser::parseTemplatesCollection
     * @covers \Aedart\Scaffold\Handlers\Utility\PropertyHandlerResolver::makePropertyHandler
     */
    public function canAskForTemplateDestination()
    {
        $command = $this->getCommandFromApp('build');

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'   => $command->getName(),
            'config'    => $this->dataPath() . 'templateOnly.scaffold.php',
        ]);

        $expectedPackageName = 'models/User.php';

        $this->assertContains($expectedPackageName, $commandTester->getDisplay());
    }
}