<?php namespace Aedart\Scaffold\Contracts\Collections;

use Aedart\Util\Interfaces\Collections\IPartialCollection;

/**
 * Files
 *
 * A collection of source files and where they must
 * be copied or moved to
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Collections
 */
interface Files extends IPartialCollection
{
    /**
     * Put a new source file and its destination into
     * this collection
     *
     * If the source file has already been added, it's
     * destination will be replaced with the new one.
     *
     * @param string $sourceFile Path to the source file
     * @param string $destination Path to where the source file should be copied or moved to
     *
     * @return bool
     */
    public function put($sourceFile, $destination);

    /**
     * Get all added source files and their
     * belonging destination
     *
     * @return string[]
     */
    public function all();
}