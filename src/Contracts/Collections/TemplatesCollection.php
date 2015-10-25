<?php namespace Aedart\Scaffold\Contracts\Collections;

use Aedart\Scaffold\Contracts\Data\Template;
use Aedart\Scaffold\Exceptions\InvalidIdException;
use Aedart\Util\Interfaces\Collections\IPartialCollection;

/**
 * <h1>Templates Collection</h1>
 *
 * A collection that contains a set of templates
 *
 * @see Template
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface TemplatesCollection extends IPartialCollection{

    /**
     * Add a template to this collection
     *
     * @param Template $template
     *
     * @return void
     *
     * @throws InvalidIdException If property has no id set
     */
    public function add(Template $template);

    /**
     * Get a template by its id
     *
     * @param string $id
     *
     * @return Template|null Returns null if no template exists in collection with given id
     */
    public function get($id);

    /**
     * Check if this collection contains a template
     * with the given id
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id);

    /**
     * Remove the template that has the given id
     *
     * @param string $id
     *
     * @return void
     */
    public function remove($id);
}