<?php
namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Handlers\ScriptsHandler;
use Aedart\Scaffold\Contracts\Scripts\CliScript;

/**
 * Execute Scripts Task
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class ExecuteScripts extends BaseTask
{
    protected $description = 'Executes CLI scripts';

    /**
     * {@inheritdoc}
     */
    public function performTask()
    {
        // Check if there are any directories to be created
        $scriptsKey = 'scripts';
        if(!$this->config->has($scriptsKey) || count($this->config->get($scriptsKey, [])) == 0){
            $this->output->note('No scripts to execute');
            return;
        }

        // Execute the scripts
        $this->executeScripts($this->config->get($scriptsKey));
    }

    /**
     * Executes the given scripts
     *
     * @param array $scripts
     */
    protected function executeScripts(array $scripts)
    {
        // Obtain the scripts handler
        $handler = $this->getScriptsHandler();

        // Create CLI scripts
        $cliScripts = [];
        foreach ($scripts as $data){
            $cliScripts[] = $this->makeCliScript($data);
        }

        // Execute the scripts
        $handler->process($cliScripts);
    }

    /**
     * Returns a new populated instance of a CLI Script
     *
     * @param string|array $data
     *
     * @return CliScript
     */
    protected function makeCliScript($data)
    {
        if(is_string($data)){
            $data = [
                'script' => $data
            ];
        }

        return (IoC::getInstance())->make(CliScript::class, $data);
    }

    /**
     * Get the script handler
     *
     * @return ScriptsHandler
     */
    protected function getScriptsHandler(){
        return $this->resolveHandler('handlers.script');
    }
}