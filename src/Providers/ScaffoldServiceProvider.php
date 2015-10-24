<?php namespace Aedart\Scaffold\Providers;

use Illuminate\Support\ServiceProvider;
use Aedart\Scaffold\Contracts\Data\AskableProperty as AskablePropertyInterface;
use Aedart\Scaffold\Data\AskableProperty;

/**
 * Class ScaffoldServiceProvider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ScaffoldServiceProvider extends ServiceProvider{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerAskableProperty();
    }

    /******************************************************
     * Register methods
     *****************************************************/

    /**
     * Register the askable-property
     */
    public function registerAskableProperty() {
        $this->app->bind(AskablePropertyInterface::class, function($app){
            return new AskableProperty();
        });
    }
}