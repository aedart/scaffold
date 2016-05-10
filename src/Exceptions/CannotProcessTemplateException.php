<?php namespace Aedart\Scaffold\Exceptions;

use RuntimeException;

/**
 * Cannot Process Template Exception
 *
 * Throw this exception whenever a template cannot be processed, e.g. when
 * it cannot build a file, based on the given template or perhaps it is
 * missing some configuration, etc.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Exceptions
 */
class CannotProcessTemplateException extends RuntimeException
{

}