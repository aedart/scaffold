<?php namespace Aedart\Scaffold\Contracts\Indexes;

/**
 * Index Directory Path
 *
 * Component is aware of a directory path of where
 * an index file is located
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface IndexDirectoryPathAware
{
    /**
     * Set the given index directory path
     *
     * @param string $path Directory path of where the index file
     * is located
     *
     * @return void
     */
    public function setIndexDirectoryPath($path);

    /**
     * Get the given index directory path
     *
     * If no index directory path has been set, this method will
     * set and return a default index directory path, if any such
     * value is available
     *
     * @see getDefaultIndexDirectoryPath()
     *
     * @return string|null index directory path or null if none index directory path has been set
     */
    public function getIndexDirectoryPath();

    /**
     * Get a default index directory path value, if any is available
     *
     * @return string|null A default index directory path value or Null if no default value is available
     */
    public function getDefaultIndexDirectoryPath();

    /**
     * Check if index directory path has been set
     *
     * @return bool True if index directory path has been set, false if not
     */
    public function hasIndexDirectoryPath();

    /**
     * Check if a default index directory path is available or not
     *
     * @return bool True of a default index directory path is available, false if not
     */
    public function hasDefaultIndexDirectoryPath();
}