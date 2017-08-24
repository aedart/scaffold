<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Console\Style\Factory as OutputStyleFactory;
use Aedart\Scaffold\Contracts\Console\Style\Factory as OutputStyleFactoryInterface;
use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Aedart\Scaffold\Tasks\TaskRunner;
use Illuminate\Support\ServiceProvider;

/**
 * Console Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->registerOutputStyleFactory();
        $this->registerConsoleTaskRunner();
    }

    /**
     * Register output style factory
     */
    protected function registerOutputStyleFactory()
    {
        $this->app->singleton(OutputStyleFactoryInterface::class, function(){
            return new OutputStyleFactory();
        });
    }

    /**
     * Register the Console Task Runner
     */
    protected function registerConsoleTaskRunner()
    {
        $this->app->bind(ConsoleTaskRunner::class, function($app){
            return new TaskRunner();
        });
    }
}