<?php namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Loggers\ConsoleWrite;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Console Logger Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ConsoleLoggerServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLogger();
    }

    /**
     * Register the console writer
     */
    protected function registerLogger()
    {
        $this->app->bind(LoggerInterface::class, function($app){
            return new ConsoleWrite(new ConsoleOutput());
        });

        $this->app->alias(LoggerInterface::class, 'log');
    }
}