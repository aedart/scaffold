<?php namespace Aedart\Scaffold\Contracts;

use Aedart\Scaffold\Contracts\Engines\TemplateEngine;

/**
 * <h1>Template Engine Aware</h1>
 *
 * The given component is aware of - and able to specify
 * a template engine.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface TemplateEngineAware {

    /**
     * Set the given template engine
     *
     * @param TemplateEngine $engine The Template-Engine that this component is using
     *
     * @return void
     */
    public function setTemplateEngine(TemplateEngine $engine);

    /**
     * Get the given template engine
     *
     * If no template engine has been set, this method will
     * set and return a default template engine, if any such
     * value is available
     *
     * @see getDefaultTemplateEngine()
     *
     * @return TemplateEngine|null template engine or null if none template engine has been set
     */
    public function getTemplateEngine();

    /**
     * Get a default template engine value, if any is available
     *
     * @return TemplateEngine|null A default template engine value or Null if no default value is available
     */
    public function getDefaultTemplateEngine();

    /**
     * Check if template engine has been set
     *
     * @return bool True if template engine has been set, false if not
     */
    public function hasTemplateEngine();

    /**
     * Check if a default template engine is available or not
     *
     * @return bool True of a default template engine is available, false if not
     */
    public function hasDefaultTemplateEngine();
}