<?php namespace Aedart\Scaffold\Exceptions;

use RuntimeException;

/**
 * Unable To Handle Element Exception
 *
 * Throw this exception, in case that a given element (a file or a directory) could not be
 * handled or processed in the desired way.
 *
 * This is a top-level type of exception, which might encapsulate various nested exceptions
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Exceptions
 */
class UnableToHandleElementException extends RuntimeException{

}