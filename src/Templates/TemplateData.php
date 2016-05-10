<?php namespace Aedart\Scaffold\Templates;

use Aedart\Scaffold\Contracts\Collections\TemplateProperties;

/**
 * Template Data Trait
 *
 * @see \Aedart\Scaffold\Contracts\Templates\TemplateDataAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Templates
 */
trait TemplateData
{
    /**
     * Collection of Template Properties.
     * These will be assigned to a given
     * template.
     *
     * @var TemplateProperties|null
     */
    protected $templateData = null;

    /**
     * Set the given template data
     *
     * @param TemplateProperties $collection Collection of Template Properties.
     * These will be assigned to a given
     * template.
     *
     * @return void
     */
    public function setTemplateData(TemplateProperties $collection)
    {
        $this->templateData = $collection;
    }

    /**
     * Get the given template data
     *
     * If no template data has been set, this method will
     * set and return a default template data, if any such
     * value is available
     *
     * @see getDefaultTemplateData()
     *
     * @return TemplateProperties|null template data or null if none template data has been set
     */
    public function getTemplateData()
    {
        if (!$this->hasTemplateData() && $this->hasDefaultTemplateData()) {
            $this->setTemplateData($this->getDefaultTemplateData());
        }
        return $this->templateData;
    }

    /**
     * Get a default template data value, if any is available
     *
     * @return TemplateProperties|null A default template data value or Null if no default value is available
     */
    public function getDefaultTemplateData()
    {
        return null;
    }

    /**
     * Check if template data has been set
     *
     * @return bool True if template data has been set, false if not
     */
    public function hasTemplateData()
    {
        return !is_null($this->templateData);
    }

    /**
     * Check if a default template data is available or not
     *
     * @return bool True of a default template data is available, false if not
     */
    public function hasDefaultTemplateData()
    {
        return !is_null($this->getDefaultTemplateData());
    }
}