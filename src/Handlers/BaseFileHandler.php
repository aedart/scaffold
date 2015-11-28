<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Model\Traits\Strings\FilenameTrait;
use Aedart\Scaffold\Contracts\Handlers\FileHandler;

/**
 * <h1>Base File-Handler</h1>
 *
 * Abstractions for all types of file-handlers
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
abstract class BaseFileHandler extends BaseHandler implements FileHandler{

    use FilenameTrait;

}