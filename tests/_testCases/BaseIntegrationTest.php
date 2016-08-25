<?php
use Aedart\Scaffold\ScaffoldApplication;
use Symfony\Component\Console\Command\Command;
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

    /**
     * Write input stream
     *
     * @param array $input
     *
     * @return resource
     */
    public function writeInput(array $input)
    {
        $input = implode('\n', $input);

        return $this->getInputStream($input);
    }
}