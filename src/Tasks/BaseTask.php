<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Model\Traits\Strings\DescriptionTrait;
use Aedart\Model\Traits\Strings\NameTrait;
use Aedart\Scaffold\Contracts\Tasks\ConsoleTask;
use Aedart\Scaffold\Containers\IoC;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
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
    use NameTrait;
    use DescriptionTrait;

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
     * Class path of the last handler
     * that was resolved
     *
     * @var string
     */
    protected $lastHandler;

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

    /**
     * Resolve a handler
     *
     * @param string $alias
     * @param array $parameters [optional]
     *
     * @return \Aedart\Scaffold\Contracts\Handlers\Handler
     * @see IoC::resolveHandler()
     *
     */
    protected function resolveHandler($alias, array $parameters = [])
    {
        // Resolve from IoC
        $ioc = IoC::getInstance();
        $handler = $ioc->resolveHandler($alias, $this->config, $parameters);

        // Output some information about what handler is being
        // applied for something
        $this->outputAppliedHandler($handler);

        // Finally, return the handler
        return $handler;
    }

    /**
     * Outputs the handler that has been applied (resolved), but
     * only if the given handler is not the same as the last
     * handler.
     *
     * @param \Aedart\Scaffold\Contracts\Handlers\Handler $handler
     */
    protected function outputAppliedHandler($handler)
    {
        $handlerClass = get_class($handler);
        if($handlerClass != $this->lastHandler){
            $this->lastHandler = $handlerClass;
            $this->output->text("Using handler: {$handlerClass}");
        }
    }

    public function getDefaultName()
    {
        $a = Str::snake(get_class($this));
        $b = explode('\\', $a);
        $c = str_replace('_', ' ', array_pop($b));

        return Str::ucfirst(trim($c));
    }
}