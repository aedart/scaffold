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

    /**
     * BaseHandler constructor.
     *
     * @param array $configuration [optional] Handler configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->populate($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function process($element)
    {
        try {
            $this->processElement($element);
        } catch(Exception $e){
            throw new UnableToProcessElementException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function populate(array $data = []) : void
    {
        // N/A - Overwrite this method if you need to accept and process
        // configuration.
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