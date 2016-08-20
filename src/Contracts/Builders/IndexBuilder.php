<?php
namespace Aedart\Scaffold\Contracts\Builders;

use Aedart\Model\Contracts\Strings\DirectoryAware;
use Aedart\Model\Contracts\Strings\FilenameAware;
use Aedart\Model\Contracts\Strings\PatternAware;
use Aedart\Scaffold\Contracts\Indexes\Index;

/**
 * Index Builder
 *
 * TODO: Desc...
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Builders
 */
interface IndexBuilder extends DirectoryAware,
    PatternAware,
    FilenameAware
{
    /**
     * Default name of the directory where
     * index file is located
     */
    const DEFAULT_SCAFFOLD_INDEX_DIRECTORY = '.scaffold/';

    /**
     * Default scaffold file name pattern
     */
    const DEFAULT_SCAFFOLD_FILE_PATTERN = '*.scaffold.php';

    /**
     * Default name of index file
     */
    const DEFAULT_INDEX_FILE = 'index.json';

    /**
     * Searches and generates an index file of all the found
     * scaffold configuration files.
     *
     * <br />
     *
     * Method will first look for an existing index and return it,
     * if it has not yet expired.
     *
     * @see Index::hasExpired()
     *
     * @param string[] $directories Paths where to search for scaffold files
     * @param bool $force [optional] If true, an index file will be build, regardless of expiration date
     * @param int $expires [optional] How many minutes from now should the index expire
     *
     * @return Index Either a new index is build or a existing (not yet expired) version is used
     */
    public function build(array $directories, $force = false, $expires = 5);

    /**
     * Loads and returns an index from the filesystem
     *
     * @return Index|null Index if one exists, null if none exists
     */
    public function load();
}