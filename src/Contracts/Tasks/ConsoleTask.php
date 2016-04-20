<?php namespace Aedart\Scaffold\Contracts\Tasks;

use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console Task
 *
 * A task that a given console command must execute
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Tasks
 */
interface ConsoleTask
{
    /**
     * Execute this given task
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Repository $config
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output, Repository $config);
}