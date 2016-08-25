<?php
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

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
        //$this->emptyPath($this->outputPath());

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
        $command = $this->getCommandFromApp('install');
        $commandTester = $this->makeCommandTester($command);

        $input = [
            '0', // Select vendor
            '0', // Select package
            '0', // Select scaffold
            'Tv', // Class Name
            'Tv.php' // Filename
        ];


        $helper = $command->getHelper('question');
        $helper->setInputStream($this->writeInput($input));

        $args = [
            'command'               => $command->getName(),
            '--output'              => $this->outputPath(),
            '--index-directories'   => $this->getVendorsList(),
            '--index-output'        => $this->outputPath() . '.scaffold/'
        ];

        $commandTester->execute($args);

        // TODO: THis will never work, because \Symfony\Component\Console\Style\SymfonyStyle's $questionHelper
        // TODO: is NEVER the same as the one inside the command. The only way to fix this is by creating
        // TODO: a custom implementation of the Style... However, it seems impossible to actually set the helper!
    }
}