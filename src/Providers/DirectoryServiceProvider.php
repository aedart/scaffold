<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Collections\Directories;
use Aedart\Scaffold\Contracts\Collections\Directories as DirectoriesInterface;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Illuminate\Support\ServiceProvider;

/**
 * Directory Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class DirectoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDirectoriesHandler();
        $this->registerDirectoriesCollection();
    }

    /**
     * Register the directories handler
     */
    protected function registerDirectoriesHandler()
    {
        $this->app->bind(DirectoriesHandlerInterface::class, function($app, array $data = []){
            return new DirectoriesHandler();
        });

        $this->app->alias(DirectoriesHandlerInterface::class, 'handlers.directory');
    }

    /**
     * Register the directories collection
     */
    protected function registerDirectoriesCollection()
    {
        $this->app->bind(DirectoriesInterface::class, function($app, array $data = []){
            return new Directories($data);
        });
    }
}