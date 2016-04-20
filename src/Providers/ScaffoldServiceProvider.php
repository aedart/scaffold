<?php namespace Aedart\Scaffold\Providers;


use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Illuminate\Support\ServiceProvider;

/**
 * Scaffold Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ScaffoldServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDirectoriesHandler();
    }

    /******************************************************
     * Register methods
     *****************************************************/

    /**
     * Register the directories handler
     */
    public function registerDirectoriesHandler()
    {
        $this->app->bind(DirectoriesHandlerInterface::class, function($app, array $data = []){
            return new DirectoriesHandler();
        });

        $this->app->alias(DirectoriesHandlerInterface::class, 'handlers.directory');
    }
}