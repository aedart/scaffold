<?php
namespace Aedart\Scaffold\Providers;

use Aedart\Scaffold\Contracts\Builders\IndexBuilder as IndexBuilderInterface;
use Aedart\Scaffold\Contracts\Indexes\Index;
use Aedart\Scaffold\Contracts\Indexes\ScaffoldLocation as ScaffoldLocationInterface;
use Aedart\Scaffold\Indexes\IndexBuilder;
use Aedart\Scaffold\Indexes\Location;
use Aedart\Scaffold\Indexes\ScaffoldIndex;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
/**
 * Index Service Provider
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Providers
 */
class IndexServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerScaffoldLocation();
        $this->registerScaffoldIndex();
        $this->registerIndexBuilder();
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
        $this->app->bind(IndexBuilderInterface::class, function($app){
            return new IndexBuilder();
        });
    }
}