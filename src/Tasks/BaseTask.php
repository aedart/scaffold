<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Contracts\Tasks\ConsoleTask;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base Task
 *
 * Abstraction for all console tasks
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
abstract class BaseTask implements ConsoleTask
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
     * @var OutputInterface|\Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $output;

    /**
     * The configuration
     *
     * @var Repository
     */
    protected $config;

    /**
     * Execute this given task
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Repository $config
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output, Repository $config)
    {
        $this->input = $input;
        $this->output = $output;
        $this->config = $config;

        $this->performTask();
    }

    /**
     * Performs the actual task execution
     *
     * @return void
     */
    abstract public function performTask();
}