<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Contracts\Tasks\ConsoleTask;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Null Task
 *
 * A blank task that does not do anything at all!
 *
 * This task is mostly useful for testing - or for
 * situations where you absolutely need a task but
 * it should not perform anything.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class NullTask extends BaseTask implements ConsoleTask
{
    protected $name = 'Null Task';

    protected $description = 'This task does not do anything';

    final public function execute(InputInterface $input, OutputInterface $output, Repository $config)
    {
        parent::execute($input, $output, $config);
    }

    final public function performTask()
    {
        return;
    }
}