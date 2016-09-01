<?php
use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\ScaffoldApplication;
use Aedart\Scaffold\Testing\Console\Style\ExtendedStyle;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\Console\Command\Command;
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

        // NB: Has to be singleton or stream might get screwed!
        $ioc->singleton(StyleInterface::class, function($app, array $data = []) use ($inputValues){
            if(!array_key_exists('input', $data) || !array_key_exists('output', $data)){

                $target = StyleInterface::class;

                $msg = "Target {$target} cannot be build. Missing arguments; e.g. ['input' => (InputInterface), 'output' => (OutputInterface)]";

                throw new BindingResolutionException($msg);
            }

            $style = new ExtendedStyle($data['input'], $data['output']);

            // Set the input stream on the question helper
            if(!empty($inputValues)){
                $style->getQuestionHelper()->setInputStream($this->writeInput($inputValues));
            }

            return $style;
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