<?php namespace Aedart\Scaffold\Console;

use Aedart\Scaffold\Containers\IoC;
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
     * @var StyleInterface
     */
    protected $output;

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $ioc = IoC::getInstance();

        $this->input = $input;
        $this->output = $ioc->make(StyleInterface::class, [
            'input'     => $input,
            'output'    => $output,
        ]);

        return $this->runCommand();
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    abstract public function runCommand();
}