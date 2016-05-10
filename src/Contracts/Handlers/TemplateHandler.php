<?php namespace Aedart\Scaffold\Contracts\Handlers;

use Aedart\Scaffold\Contracts\Templates\Template;
use Aedart\Scaffold\Contracts\Templates\TemplateDataAware;
use Aedart\Scaffold\Exceptions\CannotProcessTemplateException;

/**
 * Template Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Handlers
 */
interface TemplateHandler extends Handler,
    TemplateDataAware
{
    /**
     * Process the given template - compile and generate a file
     * based on the given template
     *
     * @see Template
     *
     * @param Template $template The template to use in order to build a file
     *
     * @return void
     *
     * @throws CannotProcessTemplateException
     */
    public function processTemplate(Template $template);
}