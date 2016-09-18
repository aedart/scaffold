<?php
namespace Aedart\Scaffold\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

/**
 * Filesystem Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->registerFilesystem();
    }

    /**
     * Register file system
     */
    protected function registerFilesystem()
    {
        $this->app->bind('files', function($app, array $data = []){
            return new Filesystem();
        });
    }
}