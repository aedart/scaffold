<?php namespace Aedart\Scaffold\Resolvers;

use Aedart\Scaffold\Exceptions\ForbiddenException;
use Aedart\Scaffold\Providers\ConsoleLoggerServiceProvider;
use Aedart\Scaffold\Providers\ScaffoldServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\App;

/**
 * IoC Service Container (Wrapper)
 *
 * This class acts first and foremost as a wrapper for Laravel's
 * service container. Secondly, additional application logic has
 * been added, in regards to resolving instances from specifiable
 * configuration repositories and bootstrapping; it ensures that
 * this package's service provider is registered.
 *
 * @see \Illuminate\Contracts\Container\Container
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Resolvers
 */
class IoC
{
    /**
     * Instance of this resolver
     *
     * @var self
     */
    private static $instance;

    /**
     * IoC Service Container instance
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * List of service providers that
     * this IoC / application is using
     *
     * @var string[]
     */
    protected $providers = [
        ConsoleLoggerServiceProvider::class,
        ScaffoldServiceProvider::class
    ];

    /**
     * Get the resolver instance
     *
     * @return self
     */
    static public function getInstance()
    {
        if(null === static::$instance){
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Boot the IoC (Alias for the getInstance method)
     *
     * @see getInstance()
     *
     * @return IoC
     */
    static public function boot()
    {
        return self::getInstance();
    }

    /**
     * IoC constructor.
     */
    private function __construct()
    {
        $this->setupContainer();
        $this->registerServiceProviders($this->providers);
    }

    /**
     * Force destroy the container
     *
     * WARNING: Avoid using this, unless you really need to
     * destroy the IoC!
     */
    public function destroy()
    {
        $this->container = null;

        App::setFacadeApplication(null);

        self::$instance = null;
    }

    /**
     * Initialise the IoC Service Container
     */
    protected function setupContainer(){
        // Only set the application on the Facade
        // if there is none yet set
        if(is_null(App::getFacadeApplication())){
            App::setFacadeApplication(new Container());
        }

        $this->container = App::getFacadeApplication();

        $this->container->singleton('app', $this->container);
    }

    /**
     * Register this application's service providers
     *
     * @param string[] $providers [optional]
     */
    public function registerServiceProviders(array $providers = [])
    {
        foreach($providers as $provider){
            (new $provider($this->container()))->register();
        }
    }

    /**
     * Resolve a given instance based on the concrete instance
     * that is found in the configuration. If there is no
     * instance that can be resolved from the config, then a
     * default instance is attempted to be resolved
     *
     * @param string $entry Configuration or instance identifier
     * @param Repository $config
     *
     * @return mixed
     */
    public function resolveFromConfig($entry, Repository $config)
    {
        if($config->has($entry)){
            $instance = $config->get($entry);
            return new $instance;
        }

        return $this->container()->make($entry);
    }

    /**
     * Alias for Container::make
     *
     * @param string $concrete
     * @param array $parameters
     *
     * @see \Illuminate\Contracts\Container\Container::make()
     *
     * @return mixed
     */
    public function make($concrete, array $parameters = [])
    {
        return $this->container()->make($concrete, $parameters);
    }

    /**
     * Resolve and configure a handler
     *
     * Method attempts to make an instance of a given handler,
     * from the given configuration, based on the alias.
     * If no alias is defined inside the configuration, a default
     * handler is attempted resolved, from the IoC.
     *
     * Furthermore, if a handler is created, it's base path and
     * output paths are also set, before it is returned. This
     * applies only if the given configuration has a 'basePath'
     * and an 'outputPath' set.
     *
     * @param string $alias
     * @param Repository $config
     *
     * @see IoC::resolveFromConfig()
     *
     * @return \Aedart\Scaffold\Contracts\Handlers\Handler
     */
    public function resolveHandler($alias, Repository $config)
    {
        /** @var \Aedart\Scaffold\Contracts\Handlers\Handler $handler */
        $handler = $this->resolveFromConfig($alias, $config);

        // Set base path
        $basePathKey = 'basePath';
        if($config->has($basePathKey)){
            $handler->setBasePath($config->get($basePathKey));
        }

        // Set output path
        $outputPath = 'outputPath';
        if($config->has($outputPath)){
            $handler->setOutputPath($config->get($outputPath));
        }

        // Finally, return the handler
        return $handler;
    }

    /**
     * Get the IoC Service Container instance
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * Prevent clone operations
     */
    final public function __clone()
    {
        throw new ForbiddenException('Class is singleton. Clone is forbidden');
    }

    /**
     * Prevent initialisation
     */
    final public function __wakeup()
    {
        throw new ForbiddenException('Class is singleton. __wakeup is forbidden');
    }
}