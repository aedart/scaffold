<?php namespace Aedart\Scaffold\Contracts\Collections;

use Aedart\Util\Interfaces\Collections\IPartialCollection;

/**
 * Directories
 *
 * A collection of directory paths
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface Directories extends IPartialCollection
{
    /**
     * Add a directory path
     *
     * @param string $path Relative or absolute path to a directory
     *
     * @return bool
     */
    public function add($path);

    /**
     * Get all added paths
     *
     * @return string[]
     */
    public function all();
}