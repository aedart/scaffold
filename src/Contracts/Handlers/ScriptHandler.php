<?php
namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Scaffold\Exceptions\ScriptFailedException;

/**
 * Script Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface ScriptHandler extends Handler
{
    /**
     * Executes the given command line script
     *
     * @param string $script
     *
     * @return void
     *
     * @throws ScriptFailedException
     */
    public function processScript($script);
}