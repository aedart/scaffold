<?php namespace Aedart\Scaffold\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     * @var OutputInterface|SymfonyStyle
     */
    protected $output;

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        return $this->runCommand();
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    abstract public function runCommand();
}