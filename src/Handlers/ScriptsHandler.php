<?php
namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Handlers\ScriptsHandler as ScriptsHandlerInterface;
use Aedart\Scaffold\Contracts\Scripts\CliScript;
use Aedart\Scaffold\Exceptions\ScriptFailedException;
use Symfony\Component\Process\Process;

/**
 * Scripts Handler
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\ScriptsHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class ScriptsHandler extends BaseHandler implements ScriptsHandlerInterface
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
        $log->info('Executing "{script}" script', ['script' => $script->getScript()]);

        // New process, with timeout
        $process = new Process($script->getScript());
        $process->setTimeout($script->getTimeout());

        // Run process
        $process->run(function($type, $buffer) use($log){
            if(Process::OUT === $type){
                $log->debug($buffer);
                return;
            }

            $log->error($buffer);
        });

        // Abort if process failed
        if(!$process->isSuccessful()){

            // Remove the error output
            $process->clearErrorOutput();

            // Throw failure
            throw new ScriptFailedException(sprintf('Script "%s" has filed', $script->getScript()));
        }
    }
}