<?php namespace Aedart\Scaffold\Contracts\Engines;

use Aedart\Model\Contracts\Arrays\DataAware;
use Aedart\Model\Contracts\Strings\BasePathAware;
use Aedart\Model\Contracts\Strings\TemplateAware;
use Aedart\Scaffold\Exceptions\CannotRenderTemplateException;

/**
 * <h1>Interface Template Engine</h1>
 *
 * A wrapper for whatever template engine might be used, in order to
 * render the given template.
 *
 * <br />
 *
 * By default, the template that must be rendered, will be located inside
 * the provided base-path. Furthermore, any eventual data that must be
 * passed to the engine, must be set as an array; `getData()`
 *
 * @see BasePathAware
 * @see TemplateAware
 * @see DataAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Engines
 */
interface TemplateEngine extends BasePathAware, TemplateAware, DataAware{

    /**
     * Initialises the underlying template engine, setups its
     * paths, plugins, configuration,... etc
     *
     * @return void
     */
    public function setup();

    /**
     * Render the given template, with all of its data, and
     * return the rendered string
     *
     * @return string
     *
     * @throws CannotRenderTemplateException In case that given template cannot be rendered
     */
    public function render();

    /**
     * Get the raw engine instance
     *
     * @return object
     */
    public function engine();
}