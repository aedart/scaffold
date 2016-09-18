<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Contracts\Handlers\ScriptsHandler as ScriptsHandlerInterface;
use Aedart\Scaffold\Contracts\Scripts\CliScript as CliScriptInterface;
use Aedart\Scaffold\Handlers\ScriptsHandler;
use Aedart\Scaffold\Scripts\CliScript;
use Illuminate\Support\ServiceProvider;

/**
 * Script Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ScriptServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->registerCliScript();
        $this->registerScriptsHandler();
    }

    /**
     * Register the Scripts handler
     */
    protected function registerScriptsHandler()
    {
        $this->app->bind(ScriptsHandlerInterface::class, function($app, array $data = []){
            return new ScriptsHandler();
        });

        $this->app->alias(ScriptsHandlerInterface::class, 'handlers.script');
    }

    /**
     * Register the CLI script object
     */
    protected function registerCliScript()
    {
        $this->app->bind(CliScriptInterface::class, function($app, array $data = []){
            return new CliScript($data, $app);
        });
    }
}