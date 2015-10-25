<?php namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Contracts\Collections\TemplatesCollection;

/**
 * <h1>Templates-Collection Trait</h1>
 *
 * @see \Aedart\Scaffold\Contracts\TemplatesCollectionAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait TemplatesCollectionTrait {

    /**
     * Templates Collection
     *
     * @var TemplatesCollection|null
     */
    protected $templates = null;

    /**
     * Set the given templates
     *
     * @param TemplatesCollection $collection Templates Collection
     *
     * @return void
     */
    public function setTemplates(TemplatesCollection $collection) {
        $this->templates = $collection;
    }

    /**
     * Get the given templates
     *
     * If no templates has been set, this method will
     * set and return a default templates, if any such
     * value is available
     *
     * @see getDefaultTemplates()
     *
     * @return TemplatesCollection|null templates or null if none templates has been set
     */
    public function getTemplates() {
        if (!$this->hasTemplates() && $this->hasDefaultTemplates()) {
            $this->setTemplates($this->getDefaultTemplates());
        }
        return $this->templates;
    }

    /**
     * Get a default templates value, if any is available
     *
     * @return TemplatesCollection|null A default templates value or Null if no default value is available
     */
    public function getDefaultTemplates() {
        return null;
    }

    /**
     * Check if templates has been set
     *
     * @return bool True if templates has been set, false if not
     */
    public function hasTemplates() {
        return !is_null($this->templates);
    }

    /**
     * Check if a default templates is available or not
     *
     * @return bool True of a default templates is available, false if not
     */
    public function hasDefaultTemplates() {
        return !is_null($this->getDefaultTemplates());
    }
}