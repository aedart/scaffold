<?php namespace Aedart\Scaffold\Contracts\Templates;

use Aedart\Scaffold\Contracts\Collections\TemplateProperties;

/**
 * Template Data Aware
 *
 * Component is aware of a collection of properties (data),
 * which can or must be assigned to a template
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Templates
 */
interface TemplateDataAware
{
    /**
     * Set the given template data
     *
     * @param TemplateProperties $collection Collection of Template Properties.
     * These will be assigned to a given
     * template.
     *
     * @return void
     */
    public function setTemplateData(TemplateProperties $collection);

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
    public function getTemplateData();

    /**
     * Get a default template data value, if any is available
     *
     * @return TemplateProperties|null A default template data value or Null if no default value is available
     */
    public function getDefaultTemplateData();

    /**
     * Check if template data has been set
     *
     * @return bool True if template data has been set, false if not
     */
    public function hasTemplateData();

    /**
     * Check if a default template data is available or not
     *
     * @return bool True of a default template data is available, false if not
     */
    public function hasDefaultTemplateData();
}