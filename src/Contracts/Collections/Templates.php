<?php namespace Aedart\Scaffold\Contracts\Collections;

use Aedart\Scaffold\Contracts\Templates\Template;
use Aedart\Util\Interfaces\Collections\IPartialCollection;

/**
 * Templates Collection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface Templates extends IPartialCollection
{
    /**
     * Put a "new" template this collection.
     *
     * If the template already exists inside the
     * collection (if id exists), then it will be
     * overridden by the new template.
     *
     * @param string $id
     * @param Template $template
     *
     * @return bool
     */
    public function put($id, Template $template);

    /**
     * Check if collection has a template with
     * the given id
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id);

    /**
     * Returns the template that has the given id
     *
     * @param string $id
     *
     * @return Template|null
     */
    public function get($id);

    /**
     * Remove the template that has the given id
     *
     * @param string $id
     *
     * @return bool
     */
    public function remove($id);

    /**
     * Get all added source files and their
     * belonging destination
     *
     * @return Template[]
     */
    public function all();
}