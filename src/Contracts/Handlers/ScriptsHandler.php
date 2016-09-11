<?php
namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Scaffold\Contracts\Scripts\CliScript;
use Aedart\Scaffold\Exceptions\ScriptFailedException;

/**
 * Scripts Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface ScriptsHandler extends Handler
{
    /**
     * Executes the given command line script
     *
     * @param CliScript $script
     *
     * @return void
     *
     * @throws ScriptFailedException
     */
    public function processScript(CliScript $script);
}