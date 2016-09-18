<?php
namespace Aedart\Scaffold\Cache;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Builders\IndexBuilder;
use Illuminate\Contracts\Cache\Repository;

/**
 * Cache Helper
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Cache
 */
class CacheHelper
{
    /**
     * Default cache directory
     */
    const DEFAULT_CACHE_DIRECTORY = IndexBuilder::DEFAULT_SCAFFOLD_INDEX_DIRECTORY . 'cache/';

    /**
     * @var Repository
     */
    static protected $cache;

    /**
     * Register the cache directory
     *
     * @param string $directory
     */
    static public function setCacheDirectory($directory)
    {
        $ioc = IoC::getInstance();

        $config = $ioc->make('config');
        $config->set('cacheDir', $directory);

        $ioc->container()['config'] = $config;
    }

    /**
     * Returns a cache repository instance
     *
     * @return Repository
     */
    static public function make()
    {
        if(!isset(self::$cache)){
            $ioc = IoC::getInstance();
            $repository = $ioc->make(Repository::class);
            self::$cache = $repository;
        }

        return self::$cache;
    }

    /**
     * Destroy the cache instance inside this helper
     */
    static public function destroy()
    {
        unset(self::$cache);
    }
}