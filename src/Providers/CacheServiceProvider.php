<?php
namespace Aedart\Scaffold\Providers;

use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Repository as RepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Cache Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->registerCache();
    }

    /**
     * Register the cache repository
     */
    protected function registerCache()
    {
        $this->app->singleton(RepositoryInterface::class, function($app){
            $store = new FileStore($app->make('files'), $app['config']['cacheDir']);
            return new Repository($store);
        });
        $this->app->alias(RepositoryInterface::class, 'cache');
    }
}