<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;
use Aedart\Scaffold\Contracts\Templates\Template;
use Aedart\Scaffold\Exceptions\CannotProcessTemplateException;
use Aedart\Scaffold\Templates\TemplateData;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigTemplateHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class TwigTemplateHandler extends BaseHandler implements TemplateHandler
{
    use TemplateData;

    /**
     * The Template Engine
     *
     * @var Twig_Environment
     */
    protected $engine = null;

    /**
     * Perform the actual element processing
     *
     * @param mixed $element
     *
     * @return void
     */
    public function processElement($element)
    {
        $this->setupEngine();
        $this->generateFileFrom($element);
    }

    /**
     * Setup the template engine
     */
    protected function setupEngine()
    {
        // New loader instance, set the base path
        $loader = new Twig_Loader_Filesystem([$this->getBasePath()]);

        // Create new instance of the Twig engine
        $this->engine = new Twig_Environment($loader, [
            'strict_variables'      => true,
        ]);
    }

    /**
     * Render the given template
     *
     * @param Template $template
     * @param array $data [optional] Context data to be assigned to the template
     *
     * @return string
     */
    protected function renderTemplate(Template $template, array $data = [])
    {
        return $this->engine
            ->loadTemplate($this->getBasePath() . $template->getSource())
            ->render($data);
    }

    protected function generateFileFrom(Template $template)
    {
        // TODO: Fail if source does not exist!
        // TODO: Fail if destination does exist!
        // TODO: Fetch the template data - if any available...
        // TODO: Try catch render
        // TODO: Persist the compiled file
    }

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
    public function processTemplate(Template $template)
    {
        $this->process($template);
    }
}