<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Aedart\Scaffold\Tasks\TaskRunner;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $this->registerConsoleOutputStyle();
        $this->registerConsoleTaskRunner();
    }

    /**
     * Register a console output style
     *
     * @see \Symfony\Component\Console\Style\StyleInterface
     */
    protected function registerConsoleOutputStyle()
    {
        $this->app->bind(StyleInterface::class, function($app, array $data = []){
            if(!array_key_exists('input', $data) || !array_key_exists('output', $data)){

                $target = StyleInterface::class;

                $msg = "Target {$target} cannot be build. Missing arguments; e.g. ['input' => (InputInterface), 'output' => (OutputInterface)]";

                throw new BindingResolutionException($msg);
            }

            return new SymfonyStyle($data['input'], $data['output']);
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