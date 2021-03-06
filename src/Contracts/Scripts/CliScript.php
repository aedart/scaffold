<?php
namespace Aedart\Scaffold\Contracts\Scripts;

use Aedart\Model\Contracts\Integers\TimeoutAware;
use Aedart\Model\Contracts\Strings\ScriptAware;
use Aedart\Util\Interfaces\Populatable;

/**
 * Cli Script
 *
 * <br />
 *
 * Some kind of CLI command (or script) that can be executed.
 *
 * <br />
 *
 * Timeout is stated in seconds.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Scripts
 */
interface CliScript extends TimeoutAware,
    ScriptAware,
    Populatable
{

}