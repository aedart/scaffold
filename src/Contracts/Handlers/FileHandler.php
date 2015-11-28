<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Model\Contracts\Strings\FilenameAware;

/**
 * <h1>Interface File-Handler</h1>
 *
 * Typically, a file handler must ensure to copy a specified "element" (a file),
 * and paste it into the given output-path, with the provided filename. However,
 * a file handler might also do some processing of the given element, before it
 * just copies it into the desired location. This for instance be anything from
 * modifying the file's timestamps (modification time), or perhaps its read and
 * write permissions.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface FileHandler extends Handler, FilenameAware{

}