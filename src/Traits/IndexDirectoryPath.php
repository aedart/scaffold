<?php namespace Aedart\Scaffold\Traits;

/**
 * Index Directory Path
 *
 * @see \Aedart\Scaffold\Contracts\Indexes\IndexDirectoryPathAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait IndexDirectoryPath
{
    /**
     * Directory path of where the index file
     * is located
     *
     * @var string|null
     */
    protected $indexDirectoryPath = null;

    /**
     * Set the given index directory path
     *
     * @param string $path Directory path of where the index file
     * is located
     *
     * @return void
     */
    public function setIndexDirectoryPath($path)
    {
        $this->indexDirectoryPath = (string) $path;
    }

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
    public function getIndexDirectoryPath()
    {
        if (!$this->hasIndexDirectoryPath() && $this->hasDefaultIndexDirectoryPath()) {
            $this->setIndexDirectoryPath($this->getDefaultIndexDirectoryPath());
        }
        return $this->indexDirectoryPath;
    }

    /**
     * Get a default index directory path value, if any is available
     *
     * @return string|null A default index directory path value or Null if no default value is available
     */
    public function getDefaultIndexDirectoryPath()
    {
        return null;
    }

    /**
     * Check if index directory path has been set
     *
     * @return bool True if index directory path has been set, false if not
     */
    public function hasIndexDirectoryPath()
    {
        return !is_null($this->indexDirectoryPath);
    }

    /**
     * Check if a default index directory path is available or not
     *
     * @return bool True of a default index directory path is available, false if not
     */
    public function hasDefaultIndexDirectoryPath()
    {
        return !is_null($this->getDefaultIndexDirectoryPath());
    }
}