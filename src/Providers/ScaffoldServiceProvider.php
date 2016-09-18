<?php namespace Aedart\Scaffold\Providers;

use Aedart\Config\Loader\Providers\ConfigurationLoaderServiceProvider;
use Illuminate\Support\AggregateServiceProvider;

/**
 * Scaffold Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ScaffoldServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        FilesystemServiceProvider::class,
        ConfigServiceProvider::class,
        ConfigurationLoaderServiceProvider::class,
        ConsoleLoggerServiceProvider::class,
        ConsoleServiceProvider::class,
        DirectoryServiceProvider::class,
        FileServiceProvider::class,
        TemplateServiceProvider::class,
        IndexServiceProvider::class,
        ScriptServiceProvider::class
    ];

    public function register()
    {
        $this->instances = [];

        // Modified version!
        foreach ($this->providers as $provider) {
            $this->instances[] = (new $provider($this->app))->register();
        }
    }
}