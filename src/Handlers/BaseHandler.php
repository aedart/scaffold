<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Laravel\Helpers\Traits\Config\ConfigTrait;
use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\OutputPathTrait;
use Aedart\Scaffold\Contracts\Handlers\Handler;
use Aedart\Scaffold\Exceptions\UnableToHandleElementException;
use Exception;

/**
 * <h1>Base Handler</h1>
 *
 * Abstractions for all types of handlers
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
abstract class BaseHandler implements Handler{

    use FileTrait, ConfigTrait, OutputPathTrait, BasePathTrait;

    public function handle($element) {
        try {
            $this->processElement($element);
        } catch (Exception $e){
            throw new UnableToHandleElementException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Process the given file or directory
     *
     * @see handle()
     *
     * @param string $element Full path to a given file or directory that must be "handled"
     *
     * @return void
     */
    abstract public function processElement($element);
}