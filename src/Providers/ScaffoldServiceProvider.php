<?php namespace Aedart\Scaffold\Providers;


use Aedart\Scaffold\Collections\Directories;
use Aedart\Scaffold\Collections\Files;
use Aedart\Scaffold\Contracts\Collections\Directories as DirectoriesInterface;
use Aedart\Scaffold\Contracts\Collections\Files as FilesInterface;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\FilesHandler as FilesHandlerInterface;
use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Handlers\FilesHandler;
use Aedart\Scaffold\Tasks\TaskRunner;
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
        $this->registerFilesHandler();
        $this->registerFilesCollection();
        $this->registerConsoleTaskRunner();
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

    /**
     * Register the files handler
     */
    protected function registerFilesHandler()
    {
        $this->app->bind(FilesHandlerInterface::class, function($app, array $data = []){
            return new FilesHandler();
        });

        $this->app->alias(FilesHandlerInterface::class, 'handlers.file');
    }

    /**
     * Register the Files collection
     */
    protected function registerFilesCollection()
    {
        $this->app->bind(FilesInterface::class, function($app, array $data = []){
            return new Files($data);
        });
    }

    /**
     * Register the Console Task Runner
     */
    protected function registerConsoleTaskRunner()
    {
        $this->app->bind(ConsoleTaskRunner::class, function($app, array $data = []){
            return new TaskRunner();
        });
    }
}