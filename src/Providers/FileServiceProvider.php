<?php

namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Collections\Files;
use Aedart\Scaffold\Contracts\Collections\Files as FilesInterface;
use Aedart\Scaffold\Contracts\Handlers\FilesHandler as FilesHandlerInterface;
use Aedart\Scaffold\Handlers\FilesHandler;
use Illuminate\Support\ServiceProvider;

/**
 * File Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class FileServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->registerFilesHandler();
        $this->registerFilesCollection();
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
}