<?php
namespace Aedart\Scaffold\Traits;

use Aedart\Scaffold\Cache\CacheHelper;
use Aedart\Scaffold\Containers\IoC;
use Illuminate\Contracts\Config\Repository;

trait CacheConfigurator
{
    /**
     * Configure the cache
     *
     * @param Repository $config
     * @param string $cachePath
     */
    protected function configureCache(Repository $config, $cachePath)
    {
        // Set the IoC's configuration instance
        $ioc = IoC::getInstance();
        $ioc->container()['config'] = $config;

        // Set the cache directory
        CacheHelper::setCacheDirectory($cachePath);
    }
}