<?php namespace Aedart\Scaffold\Engines;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * <h1>Twig</h1>
 *
 * Wrapper for the Twig Template Engine
 *
 * @see \Aedart\Scaffold\Contracts\Engines\TemplateEngine
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Engines
 */
class Twig extends BaseEngine {

    /**
     * Instance of the actual engine
     *
     * @var Twig_Environment
     */
    protected $twig = null;

    /**
     * Initialises the underlying template engine, setups its
     * paths, plugins, configuration,... etc
     *
     * @return void
     */
    public function setup() {
        // New loader instance, set the base path
        $loader = new Twig_Loader_Filesystem([$this->getBasePath()]);

        // The engine instance
        $this->twig = new Twig_Environment($loader, [
            'strict_variables'      => true,
        ]);

        // TODO: ... Some custom filters might be needed, e.g. camel-case
    }

    /**
     * Get the raw engine instance
     *
     * @return Twig_Environment
     */
    public function engine() {
        return $this->twig;
    }

    /**
     * Render the given template, with all of its data, and
     * return the rendered string
     *
     * @return string Rendered template
     */
    public function doRender(){
        return $this->engine()
            ->loadTemplate($this->getTemplate())
            ->render($this->getData());
    }
}