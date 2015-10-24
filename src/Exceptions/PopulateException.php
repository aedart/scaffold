<?php namespace Aedart\Scaffold\Exceptions;

use RuntimeException;

/**
 * Populate Exception
 *
 * Throw this exception when something could not be populated.
 * This could be due to invalid arguments, but perhaps also some
 * state conditions that are not as desired
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Exceptions
 */
class PopulateException extends RuntimeException{

}