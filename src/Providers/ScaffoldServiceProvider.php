<?php namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Collections\Directories;
use Aedart\Scaffold\Collections\Files;
use Aedart\Scaffold\Collections\TemplateProperties;
use Aedart\Scaffold\Collections\Templates;
use Aedart\Scaffold\Contracts\Builders\IndexBuilder as IndexBuilderInterface;
use Aedart\Scaffold\Contracts\Collections\Directories as DirectoriesInterface;
use Aedart\Scaffold\Contracts\Collections\Files as FilesInterface;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties as TemplatePropertiesInterface;
use Aedart\Scaffold\Contracts\Collections\Templates as TemplatesInterface;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirectoriesHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\FilesHandler as FilesHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\PropertyHandler as PropertyHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\ScriptsHandler as ScriptsHandlerInterface;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;
use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation as ScaffoldLocationInterface;
use Aedart\Scaffold\Contracts\Scripts\CliScript as CliScriptInterface;
use Aedart\Scaffold\Contracts\Tasks\ConsoleTaskRunner;
use Aedart\Scaffold\Contracts\Templates\Data\Property as PropertyInterface;
use Aedart\Scaffold\Contracts\Templates\Template as TemplateInterface;
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Handlers\FilesHandler;
use Aedart\Scaffold\Handlers\PropertyHandler;
use Aedart\Scaffold\Handlers\ScriptsHandler;
use Aedart\Scaffold\Handlers\TwigTemplateHandler;
use Aedart\Scaffold\Indexes\IndexBuilder;
use Aedart\Scaffold\Indexes\Location;
use Aedart\Scaffold\Indexes\ScaffoldIndex;
use Aedart\Scaffold\Scripts\CliScript;
use Aedart\Scaffold\Tasks\TaskRunner;
use Aedart\Scaffold\Templates\Data\Property;
use Aedart\Scaffold\Templates\Template;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Scaffold Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class ScaffoldServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFilesystem();
        $this->registerConfig();
        $this->registerConsoleOutputStyle();
        $this->registerDirectoriesHandler();
        $this->registerDirectoriesCollection();
        $this->registerFilesHandler();
        $this->registerFilesCollection();
        $this->registerConsoleTaskRunner();
        $this->registerTemplatePropertiesCollection();
        $this->registerTemplateDataProperty();
        $this->registerPropertyHandler();
        $this->registerTemplate();
        $this->registerTemplatesCollection();
        $this->registerTemplateHandler();
        $this->registerScaffoldLocation();
        $this->registerScaffoldIndex();
        $this->registerIndexBuilder();
        $this->registerCliScript();
        $this->registerScriptsHandler();
    }

    /******************************************************
     * Register methods
     *****************************************************/

    /**
     * Register file system
     */
    protected function registerFilesystem()
    {
        $this->app->bind('files', function($app, array $data = []){
            return new Filesystem();
        });
    }

    /**
     * Register the configuration repository
     */
    protected function registerConfig()
    {
        $this->app->bind(RepositoryInterface::class, function($app, array $data = []){
            return new Repository();
        });

        $this->app->alias(RepositoryInterface::class, 'config');
    }

    /**
     * Register the directories handler
     */
    protected function registerDirectoriesHandler()
    {
        $this->app->bind(DirectoriesHandlerInterface::class, function($app, array $data = []){
            return new DirectoriesHandler();
        });

        $this->app->alias(DirectoriesHandlerInterface::class, 'handlers.directory');
    }

    /**
     * Register the directories collection
     */
    protected function registerDirectoriesCollection()
    {
        $this->app->bind(DirectoriesInterface::class, function($app, array $data = []){
            return new Directories($data);
        });
    }

    /**
     * Register the files handler
     */
    protected function registerFilesHandler()
    {
        $this->app->bind(FilesHandlerInterface::class, function($app, array $data = []){
            return new FilesHandler();
        });

        $this->app->alias(FilesHandlerInterface::class, 'handlers.file');
    }

    /**
     * Register the Files collection
     */
    protected function registerFilesCollection()
    {
        $this->app->bind(FilesInterface::class, function($app, array $data = []){
            return new Files($data);
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

    /**
     * Register the scaffold location object
     */
    protected function registerScaffoldLocation()
    {
        $this->app->bind(ScaffoldLocationInterface::class, function($app, array $data = []){
            return new Location($data, $app);
        });
    }

    /**
     * Register the scaffold index object
     */
    protected function registerScaffoldIndex()
    {
        $this->app->bind(Index::class, function($app, array $data = []){
            return new ScaffoldIndex($data);
        });
    }

    /**
     * Register the index builder
     */
    protected function registerIndexBuilder()
    {
        $this->app->bind(IndexBuilderInterface::class, function($app, array $data = []){
            if(!isset($data['output'])){
                $target = IndexBuilderInterface::class;
                $msg = "Target {$target} cannot be build. Missing arguments; e.g. ['output' => (StyleInterface)]";

                throw new BindingResolutionException($msg);
            }

            return new IndexBuilder($data['output']);
        });
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