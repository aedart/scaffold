<?php namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Contracts\Engines\TemplateEngine;
use Aedart\Scaffold\Facades\Engine;

/**
 * <h1>Template Engine Trait</h1>
 *
 * @see \Aedart\Scaffold\Contracts\TemplateEngineAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait TemplateEngineTrait {

    /**
     * The Template-Engine that this component is using
     *
     * @var TemplateEngine|null
     */
    protected $templateEngine = null;

    /**
     * Set the given template engine
     *
     * @param TemplateEngine $engine The Template-Engine that this component is using
     *
     * @return void
     */
    public function setTemplateEngine(TemplateEngine $engine) {
        $this->templateEngine = $engine;
    }

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
    public function getTemplateEngine() {
        if (!$this->hasTemplateEngine() && $this->hasDefaultTemplateEngine()) {
            $this->setTemplateEngine($this->getDefaultTemplateEngine());
        }
        return $this->templateEngine;
    }

    /**
     * Get a default template engine value, if any is available
     *
     * @return TemplateEngine|null A default template engine value or Null if no default value is available
     */
    public function getDefaultTemplateEngine() {
        return Engine::getFacadeRoot();
    }

    /**
     * Check if template engine has been set
     *
     * @return bool True if template engine has been set, false if not
     */
    public function hasTemplateEngine() {
        return !is_null($this->templateEngine);
    }

    /**
     * Check if a default template engine is available or not
     *
     * @return bool True of a default template engine is available, false if not
     */
    public function hasDefaultTemplateEngine() {
        return !is_null($this->getDefaultTemplateEngine());
    }
}