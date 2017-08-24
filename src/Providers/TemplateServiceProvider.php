<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Collections\TemplateProperties;
use Aedart\Scaffold\Collections\Templates;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties as TemplatePropertiesInterface;
use Aedart\Scaffold\Contracts\Collections\Templates as TemplatesInterface;
use Aedart\Scaffold\Contracts\Handlers\PropertyHandler as PropertyHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;
use Aedart\Scaffold\Contracts\Templates\Data\Property as PropertyInterface;
use Aedart\Scaffold\Contracts\Templates\Template as TemplateInterface;
use Aedart\Scaffold\Handlers\PropertyHandler;
use Aedart\Scaffold\Handlers\TwigTemplateHandler;
use Aedart\Scaffold\Templates\Data\Property;
use Aedart\Scaffold\Templates\Template;
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
        $this->app->bind(TemplatePropertiesInterface::class, function($app){
            return new TemplateProperties();
        });
    }

    /**
     * Register the Template Data Property
     */
    protected function registerTemplateDataProperty()
    {
        $this->app->bind(PropertyInterface::class, function($app){
            return new Property([], $app);
        });
    }

    /**
     * Register property handler factory
     */
    protected function registerPropertyHandler()
    {
        $this->app->bind(PropertyHandlerInterface::class, function($app){
            return new PropertyHandler();
        });

        $this->app->alias(PropertyHandlerInterface::class, 'handlers.property');
    }

    /**
     * Register the Template
     */
    protected function registerTemplate()
    {
        $this->app->bind(TemplateInterface::class, function($app){
            return new Template([], $app);
        });
    }

    /**
     * Register the Templates Collection
     */
    protected function registerTemplatesCollection()
    {
        $this->app->bind(TemplatesInterface::class, function($app){
            return new Templates();
        });
    }

    /**
     * Register the default Template Handler
     */
    protected function registerTemplateHandler()
    {
        $this->app->bind(TemplateHandler::class, function($app){
            return new TwigTemplateHandler();
        });

        $this->app->alias(TemplateHandler::class, 'handlers.template');
    }
}