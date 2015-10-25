<?php namespace Aedart\Scaffold\Contracts;

use Aedart\Scaffold\Contracts\Collections\TemplatesCollection;

/**
 * <h1>Templates Collection Aware</h1>
 *
 * Component is aware of a templates collection, which can be
 * set and retrieved
 *
 * @see TemplatesCollection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface TemplatesCollectionAware {

    /**
     * Set the given templates
     *
     * @param TemplatesCollection $collection Templates Collection
     *
     * @return void
     */
    public function setTemplates(TemplatesCollection $collection);

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
    public function getTemplates();

    /**
     * Get a default templates value, if any is available
     *
     * @return TemplatesCollection|null A default templates value or Null if no default value is available
     */
    public function getDefaultTemplates();

    /**
     * Check if templates has been set
     *
     * @return bool True if templates has been set, false if not
     */
    public function hasTemplates();

    /**
     * Check if a default templates is available or not
     *
     * @return bool True of a default templates is available, false if not
     */
    public function hasDefaultTemplates();
}