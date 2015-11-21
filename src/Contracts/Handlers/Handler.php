<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Laravel\Helpers\Contracts\Config\ConfigAware;
use Aedart\Laravel\Helpers\Contracts\Filesystem\FileAware;
use Aedart\Model\Contracts\Strings\BasePathAware;
use Aedart\Model\Contracts\Strings\OutputPathAware;
use Aedart\Scaffold\Exceptions\UnableToHandleElementException;

/**
 * <b>Interface Handler</b>
 *
 * A handler is responsible for processing a given element, that can either be
 * a file or a directory, and finally copy or generate some kind of output, in
 * the specified output path.
 *
 * @see OutputPathAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface Handler extends OutputPathAware,
    FileAware,
    ConfigAware,
    BasePathAware
{

    /**
     * Handle the given file or directory in some way, e.g. copy it into
     * the given output-path. In case of a file, perhaps it needs to be
     * processed in some way, before it somehow is placed into the
     * desired location.
     *
     * @param string $element Full path to a given file or directory that must be "handled"
     *
     * @return void
     *
     * @throws UnableToHandleElementException
     */
    public function handle($element);
}