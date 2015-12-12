<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Model\Contracts\Arrays\DataAware;
use Aedart\Scaffold\Contracts\TemplateEngineAware;

/**
 * <h1>Interface Template-Handler</h1>
 *
 * Responsible for parsing a given template and creating
 * a given file from the specified template. Eventual data is
 * passed to this handler's template-engine.
 *
 * @see TemplateEngineAware
 * @see DataAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface TemplateHandler extends FileHandler,
    DataAware,
    TemplateEngineAware
{

}