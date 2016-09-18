<?php
namespace Aedart\Scaffold\Providers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Config Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register the configuration repository
     */
    protected function registerConfig()
    {
        $this->app->bind(RepositoryInterface::class, function($app, array $data = []){
            return new Repository();
        });

        $this->app->alias(RepositoryInterface::class, 'config');
    }
}