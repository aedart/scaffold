<?php namespace Aedart\Scaffold\Providers;


use Aedart\Scaffold\Collections\Directories;
use Aedart\Scaffold\Contracts\Collections\Directories as DirectoriesInterface;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryInterface;
use Illuminate\Filesystem\Filesystem;
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
        $this->registerFilesystem();
        $this->registerConfig();
        $this->registerDirectoriesHandler();
        $this->registerDirectoriesCollection();
    }

    /******************************************************
     * Register methods
     *****************************************************/

    /**
     * Register file system
     */
    protected function registerFilesystem()
    {
        $this->app->bind('files', function($app, array $data = []){
            return new Filesystem();
        });
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