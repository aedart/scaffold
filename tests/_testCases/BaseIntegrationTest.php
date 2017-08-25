<?php

use Aedart\Scaffold\Console\Style\ExtendedStyleFactory;
use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Console\Style\Factory;
use Aedart\Scaffold\ScaffoldApplication;
use Aedart\Scaffold\Testing\Console\Style\ExtendedStyle;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Base Integration Test
 *
 * The core test-class for all integration tests
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class BaseIntegrationTest extends BaseUnitTest
{
    protected function _before()
    {
        parent::_before();
    }

    protected function _after()
    {
        parent::_after();
    }

    /********************************************************
     * Helpers
     *******************************************************/

    public function outputPath()
    {
        return parent::outputPath() . 'commands/';
    }

    public function dataPath()
    {
        return parent::dataPath() . 'commands/';
    }

    /**
     * Returns a command from the Scaffold Application
     *
     * @param string $name
     *
     * @return Command
     */
    public function getCommandFromApp($name)
    {
        $app  = new ScaffoldApplication();

        return $app->find($name);
    }

    /**
     * Returns a new command tester instance
     *
     * @param Command $command
     *
     * @return CommandTester
     */
    public function makeCommandTester(Command $command)
    {
        return new CommandTester($command);
    }

    /**
     * Override the output style to be used inside commands,
     * and set it's question helper's input stream
     *
     * @param array $inputValues [optional]
     */
    public function registerExtendedStyle(array $inputValues = [])
    {
        $ioc = IoC::getInstance()->container();

        $ioc->singleton(Factory::class, function() use($inputValues){
            return new ExtendedStyleFactory($this->writeInput($inputValues));
        });
    }

    /**
     * Executes the given command, with the given arguments and
     * input values for interaction
     *
     * @param string $name Command name
     * @param array $args [optional] Command arguments
     * @param array $input [optional] Values for input stream
     */
    public function executeInteractiveCommand($name, array $args = [], array $input = [])
    {
        $command = $this->getCommandFromApp($name);
        $commandTester = $this->makeCommandTester($command);

        $this->registerExtendedStyle($input);

        $args['command'] = $command->getName();

        // See https://github.com/aedart/scaffold/issues/1
        // See http://symfony.com/doc/master/components/console/helpers/questionhelper.html
        // NOTE: Didn't work as expected
        //$commandTester->setInputs($input);

        $commandTester->execute($args, ['interactive' => true]);
    }
}