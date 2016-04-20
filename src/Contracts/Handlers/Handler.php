<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Laravel\Helpers\Contracts\Filesystem\FileAware;
use Aedart\Laravel\Helpers\Contracts\Logging\LogAware;
use Aedart\Model\Contracts\Strings\BasePathAware;
use Aedart\Model\Contracts\Strings\OutputPathAware;
use Aedart\Scaffold\Exceptions\UnableToProcessElementException;

/**
 * Handler
 *
 * A handler is responsible for processing some kind of an
 * element
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface Handler extends BasePathAware,
    OutputPathAware,
    FileAware,
    LogAware
{

    /**
     * Process the given element
     *
     * @param mixed $element
     *
     * @return void
     *
     * @throws UnableToProcessElementException
     */
    public function process($element);
}