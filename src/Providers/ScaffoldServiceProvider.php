<?php namespace Aedart\Scaffold\Providers;

use Illuminate\Support\ServiceProvider;
use Aedart\Scaffold\Contracts\Data\Template as TemplateInterface;
use Aedart\Scaffold\Data\Template;
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
        $this->registerTemplate();
    }

    /******************************************************
     * Register methods
     *****************************************************/

    /**
     * Register the askable-property (dto)
     */
    public function registerAskableProperty() {
        $this->app->bind(AskablePropertyInterface::class, function($app, array $data = []){
            return new AskableProperty($data);
        });
    }

    /**
     * Register the template (dto)
     */
    public function registerTemplate() {
        $this->app->bind(TemplateInterface::class, function($app, array $data = []){
            return new Template($data);
        });
    }
}