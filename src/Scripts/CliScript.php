<?php
namespace Aedart\Scaffold\Scripts;

use Aedart\DTO\DataTransferObject;
use Aedart\Model\Traits\Integers\TimeoutTrait;
use Aedart\Model\Traits\Strings\ScriptTrait;
use Aedart\Scaffold\Contracts\Scripts\CliScript as CliScriptInterface;

/**
 * Cli Script
 *
 * @see \Aedart\Scaffold\Contracts\Scripts\CliScript
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Scripts
 */
class CliScript extends DataTransferObject implements CliScriptInterface
{
    use TimeoutTrait;
    use ScriptTrait;

    /**
     * {@inheritdoc}
     */
    public function getDefaultTimeout()
    {
        return 60;
    }
}