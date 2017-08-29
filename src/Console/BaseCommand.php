<?php namespace Aedart\Scaffold\Console;

use Aedart\Scaffold\Console\Style\ExtendedStyle;
use Aedart\Scaffold\Console\Style\ExtendedStyleFactory;
use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Console\Style\Factory;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Base Command
 *
 * The base command for all scaffold console commands
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
abstract class BaseCommand extends Command
{
    /**
     * The input
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * The output
     *
     * @var StyleInterface|OutputInterface|ExtendedStyle
     */
    protected $output;

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Set input
        $this->input = $input;

        // Set output
        if($output instanceof StyleInterface){
            $this->output = $output;
        } else {
            // Means that we should wrap the output
            /** @var Factory $factory */
            $factory = IoC::getInstance()->make(Factory::class);
            $this->output = $factory->make($input, $output);
        }

        return $this->runCommand();
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    abstract public function runCommand();

    /********************************************************
     * CLI input / output helpers
     *******************************************************/

    /**
     * Returns a input stream
     *
     * Utility method for writing an input stream
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
        $input = implode(PHP_EOL, $input);

        return $this->getInputStream($input);
    }

    /**
     * Set the input for the question helper
     *
     * @param array $input [optional] Answers to questions asked
     */
    public function setQuestionHelperInput(array $input = [])
    {
        // If no input given, skip...
        if(empty($input)){
            return;
        }

        // Fail if output is not ext-style
        if( ! ($this->output instanceof ExtendedStyle)){
            throw new RuntimeException('Unable to set input. The output must be instance of ExtendedStyle!');
        }

        // Set the input stream onto the question helper
        $this->output->getQuestionHelper()->setInputStream(
            $this->writeInput($input)
        );
    }
}