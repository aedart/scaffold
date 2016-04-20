<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Laravel\Helpers\Traits\Logging\LogTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\OutputPathTrait;
use Aedart\Scaffold\Contracts\Handlers\Handler;
use Aedart\Scaffold\Exceptions\UnableToProcessElementException;
use Exception;

/**
 * Base Handler
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
abstract class BaseHandler implements Handler
{
    use BasePathTrait;
    use OutputPathTrait;
    use FileTrait;
    use LogTrait;

    public function process($element)
    {
        try {
            $this->processElement($element);
        } catch(Exception $e){
            $this->getLog()->error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw new UnableToProcessElementException('Cannot process element', $e->getCode(), $e);
        }
    }

    /**
     * Perform the actual element processing
     *
     * @param mixed $element
     *
     * @return void
     */
    abstract public function processElement($element);
}