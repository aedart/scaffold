<?php namespace Aedart\Scaffold\Providers;

use Illuminate\Support\ServiceProvider;
use Aedart\Scaffold\Contracts\Collections\AskablePropertiesCollection as AskablePropertiesCollectionInterface;
use Aedart\Scaffold\Collections\AskablePropertiesCollection;
use Aedart\Scaffold\Contracts\Collections\TemplatesCollection as TemplatesCollectionInterface;
use Aedart\Scaffold\Collections\TemplatesCollection;
use Aedart\Scaffold\Contracts\Data\Scaffold as ScaffoldInterface;
use Aedart\Scaffold\Data\Scaffold;
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
        $this->registerAskablePropertiesCollection();
        $this->registerTemplatesCollection();
        $this->registerScaffold();
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

    /**
     * Register the scaffold (dto)
     */
    public function registerScaffold() {
        $this->app->bind(ScaffoldInterface::class, function($app, array $data = []){
            return new Scaffold($data);
        });
    }

    /**
     * Register the askable-properties collection
     */
    public function registerAskablePropertiesCollection() {
        $this->app->bind(AskablePropertiesCollectionInterface::class, function($app, array $data = []){
            return new AskablePropertiesCollection($data);
        });
    }

    /**
     * Register the templates collection
     */
    public function registerTemplatesCollection() {
        $this->app->bind(TemplatesCollectionInterface::class, function($app, array $data = []){
            return new TemplatesCollection($data);
        });
    }
}