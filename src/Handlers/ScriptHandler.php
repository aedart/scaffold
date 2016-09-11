<?php
namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Handlers\ScriptHandler as ScriptHandlerInterface;
use Aedart\Scaffold\Contracts\Scripts\CliScript;
use Aedart\Scaffold\Exceptions\ScriptFailedException;
use Symfony\Component\Process\Process;

/**
 * Script Handler
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\ScriptHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class ScriptHandler extends BaseHandler implements ScriptHandlerInterface
{
    /**
     * Perform the actual element processing
     *
     * @param mixed $element
     *
     * @return void
     */
    public function processElement($element)
    {
        foreach ($element as $script){
            $this->processScript($script);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processScript(CliScript $script)
    {
        $log = $this->getLog();
        $log->info('Executing "{script}"', ['script' => $script]);

        // New process, with timeout
        $process = new Process($script->getScript());
        $process->setTimeout($script->getTimeout());

        // Run process
        $process->run(function($type, $buffer) use($log){
            // Log on error
            if(Process::ERR === $type){
                $log->error($buffer);
            }

            $log->info($buffer);
        });

        // Abort if process failed
        if(!$process->isSuccessful()){

            // Remove the error output
            $process->clearErrorOutput();

            // Throw failure
            throw new ScriptFailedException(sprintf('Script "%s" has filed', $script));
        }
    }
}