<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Collections\TemplateProperties;
use Aedart\Scaffold\Collections\Templates;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties as TemplatePropertiesInterface;
use Aedart\Scaffold\Contracts\Collections\Templates as TemplatesInterface;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\PropertyHandler as PropertyHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;
use Aedart\Scaffold\Contracts\Templates\Data\Property as PropertyInterface;
use Aedart\Scaffold\Contracts\Templates\Template as TemplateInterface;
use Aedart\Scaffold\Handlers\PropertyHandler;
use Aedart\Scaffold\Handlers\TwigTemplateHandler;
use Aedart\Scaffold\Templates\Data\Property;
use Aedart\Scaffold\Templates\Template;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

/**
 * Template Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class TemplateServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTemplatePropertiesCollection();
        $this->registerTemplateDataProperty();
        $this->registerPropertyHandler();
        $this->registerTemplate();
        $this->registerTemplatesCollection();
        $this->registerTemplateHandler();
    }

    /**
     * Register the Template Data Property Collection
     */
    protected function registerTemplatePropertiesCollection()
    {
        $this->app->bind(TemplatePropertiesInterface::class, function($app, array $data = []){
            return new TemplateProperties($data);
        });
    }

    /**
     * Register the Template Data Property
     */
    protected function registerTemplateDataProperty()
    {
        $this->app->bind(PropertyInterface::class, function($app, array $data = []){
            return new Property($data, $app);
        });
    }

    /**
     * Register the files handler
     */
    protected function registerPropertyHandler()
    {
        $this->app->bind(PropertyHandlerInterface::class, function($app, array $data = []){
            if(!array_key_exists('config', $data) ||
                !array_key_exists('key', $data) ||
                !array_key_exists('output', $data)
            ){

                $target = PropertyHandlerInterface::class;

                $msg = "Target {$target} cannot be build. Missing arguments; e.g. ['config' => (Repository), 'key' => (string), 'output' => (StyleInterface)]";

                throw new BindingResolutionException($msg);
            }

            return new PropertyHandler($data['config'], $data['key'], $data['output']);
        });

        $this->app->alias(PropertyHandlerInterface::class, 'handlers.property');
    }

    /**
     * Register the Template
     */
    protected function registerTemplate()
    {
        $this->app->bind(TemplateInterface::class, function($app, array $data = []){
            return new Template($data, $app);
        });
    }

    /**
     * Register the Templates Collection
     */
    protected function registerTemplatesCollection()
    {
        $this->app->bind(TemplatesInterface::class, function($app, array $data = []){
            return new Templates($data);
        });
    }

    /**
     * Register the default Template Handler
     */
    protected function registerTemplateHandler()
    {
        $this->app->bind(TemplateHandler::class, function($app, array $data = []){
            if(!isset($data['directoryHandler'])){
                $target = DirectoriesHandlerInterface::class;

                $msg = "Target {$target} cannot be build, for the template handler. Missing arguments; e.g. ['directoryHandler' => (DirectoriesHandler)]";

                throw new BindingResolutionException($msg);
            }

            $collection = null;
            if(isset($data['templateData'])){
                $collection = $data['templateData'];
            }

            return new TwigTemplateHandler($data['directoryHandler'], $collection);
        });

        $this->app->alias(TemplateHandler::class, 'handlers.template');
    }
}